<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use RuntimeException;

class TestClassThrowingConstructor
{
	public function __construct()
	{
		throw new RuntimeException('constructor failed');
	}
}
