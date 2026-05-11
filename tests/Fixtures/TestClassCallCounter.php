<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

use Celemas\Wire\Call;

#[Call('increment')]
class TestClassCallCounter
{
	public int $calls = 0;

	public function increment(): void
	{
		$this->calls++;
	}
}
