<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

class TestClassUntypedConstructor
{
	public function __construct(
		public $param,
	) {}
}
