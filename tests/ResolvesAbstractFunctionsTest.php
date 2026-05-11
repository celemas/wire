<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests;

use Celemas\Wire\Creator;
use Celemas\Wire\Tests\Fixtures\ResolvesAbstractFunctionsProxy;
use Celemas\Wire\Tests\Fixtures\TestClass;
use Celemas\Wire\Tests\Fixtures\TestClassInject;
use Celemas\Wire\Tests\Fixtures\TestClassObjectArgs;
use ReflectionClass;
use ReflectionMethod;

final class ResolvesAbstractFunctionsTest extends TestCase
{
	public function testResolveParamDelegatesToParameterResolver(): void
	{
		$proxy = new ResolvesAbstractFunctionsProxy(new Creator($this->container()));
		$constructor = new ReflectionClass(TestClassObjectArgs::class)->getConstructor();

		if (is_null($constructor)) {
			$this->fail('Expected constructor to be available');
		}

		$result = $proxy->param($constructor->getParameters()[0]);

		$this->assertInstanceOf(TestClass::class, $result);
	}

	public function testResolveInjectedArgsDelegatesToArgumentResolver(): void
	{
		$proxy = new ResolvesAbstractFunctionsProxy(new Creator());
		$rfn = new ReflectionMethod(TestClassInject::class, 'injectSimpleString');
		$result = $proxy->injectedArgs($rfn);

		$this->assertSame(['literal' => 'no-entry-or-class'], $result);
	}
}
