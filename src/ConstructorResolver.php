<?php

declare(strict_types=1);

namespace Celema\Wire;

use Celema\Wire\Exception\WireException;
use Override;
use ReflectionClass;

/** @psalm-api */
class ConstructorResolver
{
	use ResolvesAbstractFunctions;

	public function __construct(
		protected readonly CreatorInterface $creator,
	) {}

	/** @param ReflectionClass|class-string $class */
	public function resolve(
		ReflectionClass|string $class,
		array $predefinedArgs = [],
		array $predefinedTypes = [],
		?callable $injectCallback = null,
	): array {
		$rcls = is_string($class) ? new ReflectionClass($class) : $class;
		$constructor = $rcls->getConstructor();

		if ($constructor) {
			return $this->resolveArgs($constructor, $predefinedArgs, $predefinedTypes, $injectCallback);
		}

		// Arguments for a class without a constructor would be dropped silently.
		if ($predefinedArgs !== []) {
			throw new WireException(
				'Unresolvable: ' . $rcls->getName() . ' has no constructor, but arguments were given',
			);
		}

		return [];
	}

	#[Override]
	public function creator(): CreatorInterface
	{
		return $this->creator;
	}
}
