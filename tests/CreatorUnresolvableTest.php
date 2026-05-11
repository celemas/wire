<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests;

use Celemas\Wire\Creator;
use Celemas\Wire\Exception\WireException;
use Celemas\Wire\Tests\Fixtures\TestClassDefault;
use Celemas\Wire\Tests\Fixtures\TestClassIntersectionTypeConstructor;
use Celemas\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use Celemas\Wire\Tests\Fixtures\TestClassUntypedConstructor;
use ReflectionException;

final class CreatorUnresolvableTest extends TestCase
{
	public function testTryToResolveUnresolvable(): void
	{
		$this->throws(WireException::class, 'Unresolvable');

		$creator = new Creator();
		$creator->create(TestClassDefault::class);
	}

	public function testRejectClassWithUntypedConstructor(): void
	{
		$this->throws(WireException::class, 'typed constructor parameters');

		$creator = new Creator();
		$creator->create(TestClassUntypedConstructor::class);
	}

	public function testRejectUnknownClass(): void
	{
		$creator = new Creator();

		try {
			$creator->create('Celemas\\Wire\\Tests\\Fixtures\\ClassThatDoesNotExist');
			$this->fail('Expected WireException to be thrown');
		} catch (WireException $e) {
			$this->assertStringContainsString(
				'Unresolvable: Celemas\\Wire\\Tests\\Fixtures\\ClassThatDoesNotExist',
				$e->getMessage(),
			);
			$this->assertInstanceOf(ReflectionException::class, $e->getPrevious());
		}
	}

	public function testRejectClassWithUnsupportedConstructorUnionTypes(): void
	{
		$this->throws(WireException::class, 'union or intersection');

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
		}
	}
}
