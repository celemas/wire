<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use Celema\Wire\Call;
use InvalidArgumentException;

#[Call('init')]
class TestClassThrowingCall
{
	public function init(): void
	{
		throw new InvalidArgumentException('call method failed');
	}
}
