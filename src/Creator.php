<?php

declare(strict_types=1);

namespace Celema\Wire;

use Celema\Wire\Exception\WireException;
use Override;
use Psr\Container\ContainerInterface as Container;
use ReflectionClass;
use Throwable;

/** @psalm-api */
class Creator implements CreatorInterface
{
	use ResolvesAbstractFunctions;

	/** @var array<class-string, ReflectionClass> */
	private static array $reflectionCache = [];

	public function __construct(
		protected readonly ?Container $container = null,
	) {}

	/** @param class-string $class */
	#[Override]
	public function create(
		string $class,
		array $predefinedArgs = [],
		array $predefinedTypes = [],
		?callable $injectCallback = null,
		string $constructor = '',
	): object {
		if ($constructor !== '') {
			// Factory method: wrap reflection lookup, let invocation bubble
			try {
				$rmethod = self::getReflectionClass($class)->getMethod($constructor);
			} catch (Throwable $e) {
				throw new WireException(
					'Unresolvable: ' . $class . '::' . $constructor . ' - ' . $e->getMessage(),
					previous: $e,
				);
			}

			$args = $this->resolveArgs(
				$rmethod,
				predefinedArgs: $predefinedArgs,
				predefinedTypes: $predefinedTypes,
				injectCallback: $injectCallback,
			);
			$instance = $rmethod->invoke(null, ...$args);
			assert(is_object($instance), 'Factory methods must return an object');
		} else {
			$instance = $this->resolveConstructor(
				$class,
				$predefinedArgs,
				$predefinedTypes,
				$injectCallback,
			);
		}

		return $this->applyCallAttributes($instance, $predefinedTypes, $injectCallback);
	}

	#[Override]
	public function resolve(
		string $id,
		array $predefinedTypes = [],
		?callable $injectCallback = null,
	): object {
		if (!$this->container?->has($id)) {
			if (!class_exists($id)) {
				throw new WireException('Unresolvable: ' . $id . ' is neither a container entry nor a class');
			}

			return $this->create($id, predefinedTypes: $predefinedTypes, injectCallback: $injectCallback);
		}

		// The container owns the entry's lifetime and configuration, including
		// its calls, so the result is returned as is.
		/** @psalm-suppress MixedAssignment */
		$instance = $this->container->get($id);

		if (!is_object($instance)) {
			throw new WireException('Unresolvable: container entry ' . $id . ' is not an object');
		}

		return $instance;
	}

	/** @param class-string $class */
	protected function resolveConstructor(
		string $class,
		array $predefinedArgs,
		array $predefinedTypes,
		?callable $injectCallback,
	): object {
		try {
			$rcls = self::getReflectionClass($class);
		} catch (Throwable $e) {
			throw new WireException(
				'Unresolvable: ' . $class . ' - ' . $e->getMessage(),
				previous: $e,
			);
		}

		if (!$rcls->isInstantiable()) {
			throw new WireException('Unresolvable: ' . $class . ' cannot be instantiated');
		}

		$args = new ConstructorResolver($this)->resolve(
			$rcls,
			predefinedArgs: $predefinedArgs,
			predefinedTypes: $predefinedTypes,
			injectCallback: $injectCallback,
		);

		return $rcls->newInstance(...$args);
	}

	protected function applyCallAttributes(
		object $instance,
		array $predefinedTypes = [],
		?callable $injectCallback = null,
	): object {
		$callAttrs = self::getReflectionClass($instance::class)->getAttributes(Call::class);

		// See if the attribute itself has one or more Call attributes. If so,
		// resolve/autowire the arguments of the method it states and call it.
		foreach ($callAttrs as $callAttr) {
			$callAttr = $callAttr->newInstance();
			$methodToResolve = $callAttr->method;

			/** @var callable $callable */
			$callable = [$instance, $methodToResolve];
			$args = new CallableResolver($this)->resolve(
				$callable,
				predefinedArgs: $callAttr->args,
				predefinedTypes: $predefinedTypes,
				injectCallback: $injectCallback,
			);
			$callable(...$args);
		}

		return $instance;
	}

	/** @param class-string $class */
	private static function getReflectionClass(string $class): ReflectionClass
	{
		return self::$reflectionCache[$class] ??= new ReflectionClass($class);
	}

	#[Override]
	public function container(): ?Container
	{
		return $this->container;
	}

	#[Override]
	public function creator(): CreatorInterface
	{
		return $this;
	}
}
