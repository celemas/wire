<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

class TestClassExtended extends TestClass
{
	public function __toString(): string
	{
		return 'Stringable extended';
	}
}
