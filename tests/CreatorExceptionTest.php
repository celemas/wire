<?php

declare(strict_types=1);

namespace Celema\Wire\Tests;

use Celema\Wire\Creator;
use Celema\Wire\Tests\Fixtures\TestClassThrowingCall;
use Celema\Wire\Tests\Fixtures\TestClassThrowingConstructor;
use Celema\Wire\Tests\Fixtures\TestClassThrowingFactory;
use InvalidArgumentException;
use LogicException;
use RuntimeException;

final class CreatorExceptionTest extends TestCase
{
	public function testConstructorExceptionBubbles(): void
	{
		$this->throws(RuntimeException::class, 'constructor failed');

		$creator = new Creator();
		$creator->create(TestClassThrowingConstructor::class);
	}

	public function testFactoryExceptionBubbles(): void
	{
		$this->throws(LogicException::class, 'factory failed');

		$creator = new Creator();
		$creator->create(TestClassThrowingFactory::class, constructor: 'build');
	}

	public function testCallAttributeExceptionBubbles(): void
	{
		$this->throws(InvalidArgumentException::class, 'call method failed');

		$creator = new Creator();
		$creator->create(TestClassThrowingCall::class);
	}
}
