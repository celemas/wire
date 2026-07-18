<?php

declare(strict_types=1);

namespace Celema\Wire;

use Psr\Container\ContainerInterface as Container;

interface CreatorInterface
{
	/** @param class-string $class */
	public function create(
		string $class,
		array $predefinedArgs = [],
		array $predefinedTypes = [],
		?callable $injectCallback = null,
		string $constructor = '',
	): object;

	public function container(): ?Container;
}
