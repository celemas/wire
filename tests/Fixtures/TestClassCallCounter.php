<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use Celema\Wire\Call;

#[Call('increment')]
class TestClassCallCounter
{
	public int $calls = 0;

	public function increment(): void
	{
		$this->calls++;
	}
}
