<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

use Celemas\Wire\Call;
use InvalidArgumentException;

#[Call('init')]
class TestClassThrowingCall
{
	public function init(): void
	{
		throw new InvalidArgumentException('call method failed');
	}
}
