<?php

declare(strict_types=1);

namespace Celema\Wire\Tests;

use Celema\Wire\Creator;
use Celema\Wire\Tests\Fixtures\Container;
use Celema\Wire\Tests\Fixtures\ScopedWireContainer;
use Celema\Wire\Tests\Fixtures\WireizedContainer;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	public function container(): Container
	{
		$container = new Container();
		$container->add(Container::class, $container);

		return $container;
	}

	public function wireContainer(): WireizedContainer
	{
		$container = new WireizedContainer();
		$container->add(Container::class, $container);

		return $container;
	}

	public function scopedWireContainer(): ScopedWireContainer
	{
		return new ScopedWireContainer();
	}

	public function creator(): Creator
	{
		return new Creator($this->container());
	}

	public function throws(string $exception, ?string $message = null): void
	{
		$this->expectException($exception);

		if ($message) {
			$this->expectExceptionMessage($message);
		}
	}
}
