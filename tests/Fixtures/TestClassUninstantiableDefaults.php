<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use Closure;

class TestClassUninstantiableDefaults
{
	public function __construct(
		public readonly TestClass $testobj,
		public readonly ?Closure $build = null,
		public readonly ?TestAbstract $abstract = null,
		public readonly TestEnum $choice = TestEnum::Second,
	) {}
}
