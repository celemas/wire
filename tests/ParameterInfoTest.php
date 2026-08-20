<?php

declare(strict_types=1);

namespace Celema\Wire\Tests;

use Celema\Wire\ParameterInfo;
use Celema\Wire\Tests\Fixtures\TestClassApp;
use Celema\Wire\Tests\Fixtures\TestClassUnionTypeConstructor;
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
			'Celema\Wire\Tests\Fixtures\TestClassUnionTypeConstructor::__construct('
				. '..., Celema\Wire\Tests\Fixtures\TestClassApp|'
				. 'Celema\Wire\Tests\Fixtures\TestClassRequest $param, ...)',
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
			'Celema\Wire\Tests\ParameterInfoTest::{closure:Celema\Wire\Tests\ParameterInfoTest'
				. '::testParameterInfoFunction():33}(..., Celema\Wire\Tests\Fixtures\TestClassApp $app, ...)',
			ParameterInfo::info($param),
		);
	}
}
