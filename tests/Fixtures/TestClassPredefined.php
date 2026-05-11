<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

class TestClassPredefined
{
	public function __construct(
		public readonly string $value,
	) {}
}
