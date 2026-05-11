<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

use LogicException;

class TestClassThrowingFactory
{
	private function __construct() {}

	public static function build(): self
	{
		throw new LogicException('factory failed');
	}
}
