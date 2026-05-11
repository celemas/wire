<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

class TestClassConstructor
{
	public function __construct(
		public readonly TestClass $testobj,
		public readonly string $value = 'default',
	) {}
}
