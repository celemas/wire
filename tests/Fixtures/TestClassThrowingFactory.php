<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use LogicException;

class TestClassThrowingFactory
{
	private function __construct() {}

	public static function build(): self
	{
		throw new LogicException('factory failed');
	}
}
