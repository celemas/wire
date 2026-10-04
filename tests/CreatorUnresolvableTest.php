<?php

declare(strict_types=1);

namespace Celema\Wire\Tests;

use Celema\Wire\Creator;
use Celema\Wire\Exception\WireException;
use Celema\Wire\Tests\Fixtures\TestClassDefault;
use Celema\Wire\Tests\Fixtures\TestClassIntersectionTypeConstructor;
use Celema\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use Celema\Wire\Tests\Fixtures\TestClassUntypedConstructor;
use ReflectionException;

final class CreatorUnresolvableTest extends TestCase
{
	public function testTryToResolveUnresolvable(): void
	{
		$this->throws(
			WireException::class,
			"Unresolvable parameter. Source: \n"
				. 'Celema\\Wire\\Tests\\Fixtures\\TestClassDefault::__construct(..., int $number, ...)',
		);

		$creator = new Creator();
		$creator->create(TestClassDefault::class);
	}

	public function testRejectClassWithUntypedConstructor(): void
	{
		$this->throws(
			WireException::class,
			"To be resolvable, classes must have fully typed constructor parameters. Source: \n"
				. 'Celema\\Wire\\Tests\\Fixtures\\TestClassUntypedConstructor::__construct(..., $param, ...)',
		);

		$creator = new Creator();
		$creator->create(TestClassUntypedConstructor::class);
	}

	public function testRejectUnknownClass(): void
	{
		$creator = new Creator();

		try {
			$creator->create('Celema\\Wire\\Tests\\Fixtures\\ClassThatDoesNotExist');
			$this->fail('Expected WireException to be thrown');
		} catch (WireException $e) {
			$this->assertInstanceOf(ReflectionException::class, $e->getPrevious());
			$this->assertSame(
				'Unresolvable: Celema\\Wire\\Tests\\Fixtures\\ClassThatDoesNotExist - '
					. $e->getPrevious()->getMessage(),
				$e->getMessage(),
			);
		}
	}

	public function testRejectClassWithUnsupportedConstructorUnionTypes(): void
	{
		$this->throws(
			WireException::class,
			"Cannot resolve union or intersection types. Source: \n"
				. 'Celema\\Wire\\Tests\\Fixtures\\TestClassUnionTypeConstructor::__construct(..., '
				. 'Celema\\Wire\\Tests\\Fixtures\\TestClassApp|Celema\\Wire\\Tests\\Fixtures\\TestClassRequest $param, ...)',
		);

		$creator = new Creator();
		$creator->create(TestClassUnionTypeConstructor::class);
	}

	public function testRejectClassWithUnsupportedConstructorIntersectionTypes(): void
	{
		$this->throws(WireException::class, 'union or intersection');

		$creator = new Creator();
		$creator->create(TestClassIntersectionTypeConstructor::class);
	}

	public function testKeepsPreviousException(): void
	{
		$creator = new Creator();

		try {
			$creator->create(TestClassDefault::class, constructor: 'missingFactory');
			$this->fail('Expected WireException to be thrown');
		} catch (WireException $e) {
			$this->assertInstanceOf(ReflectionException::class, $e->getPrevious());
			$this->assertSame(
				'Unresolvable: Celema\\Wire\\Tests\\Fixtures\\TestClassDefault::missingFactory - '
					. $e->getPrevious()->getMessage(),
				$e->getMessage(),
			);
		}
	}
}
