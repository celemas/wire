<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use Closure;

class TestClassRequiredClosure
{
	public function __construct(
		public readonly Closure $build,
	) {}
}
