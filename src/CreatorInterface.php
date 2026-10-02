<?php

declare(strict_types=1);

namespace Celema\Wire;

use Psr\Container\ContainerInterface as Container;

/** @psalm-api */
interface CreatorInterface
{
	/**
	 * Builds a new instance of `$class`. The container is only consulted for
	 * its parameters, never for `$class` itself.
	 *
	 * @param class-string $class
	 */
	public function create(
		string $class,
		array $predefinedArgs = [],
		array $predefinedTypes = [],
		?callable $injectCallback = null,
		string $constructor = '',
	): object;

	/**
	 * Returns the container's entry when `$id` is registered, so the entry's
	 * lifetime and configuration apply. Otherwise builds a new instance like
	 * `create()`.
	 */
	public function resolve(
		string $id,
		array $predefinedTypes = [],
		?callable $injectCallback = null,
	): object;

	public function container(): ?Container;
}
