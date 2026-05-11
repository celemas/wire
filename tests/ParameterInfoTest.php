<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests;

use Celemas\Wire\ParameterInfo;
use Celemas\Wire\Tests\Fixtures\TestClassApp;
use Celemas\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use ReflectionFunction;

#[CoversClass(ParameterInfo::class)]
final class ParameterInfoTest extends TestCase
{
	public function testParameterInfoClass(): void
	{
		$rcls = new ReflectionClass(TestClassUnionTypeConstructor::class);
		$constructor = $rcls->getConstructor();
		$param = $constructor->getParameters()[0];

		$this->assertSame(
			'Celemas\Wire\Tests\Fixtures\TestClassUnionTypeConstructor::__construct('
			. '..., Celemas\Wire\Tests\Fixtures\TestClassApp|'
			. 'Celemas\Wire\Tests\Fixtures\TestClassRequest $param, ...)',
			ParameterInfo::info($param),
		);
	}

	public function testParameterInfoFunction(): void
	{
		$rfun = new ReflectionFunction(static function (TestClassApp $app) {
			$app->debug();
		});
		$param = $rfun->getParameters()[0];

		$this->assertSame(
			'Celemas\Wire\Tests\ParameterInfoTest::{closure:Celemas\Wire\Tests\ParameterInfoTest'
			. '::testParameterInfoFunction():33}(..., Celemas\Wire\Tests\Fixtures\TestClassApp $app, ...)',
			ParameterInfo::info($param),
		);
	}
}
