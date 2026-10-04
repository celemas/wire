<?php

declare(strict_types=1);

namespace Celema\Wire\Tests;

use Celema\Wire\Creator;
use Celema\Wire\Exception\WireException;
use Celema\Wire\Inject;
use Celema\Wire\Tests\Fixtures\TestClass;
use Celema\Wire\Tests\Fixtures\TestClassApp;
use Celema\Wire\Tests\Fixtures\TestClassCallCounter;
use Celema\Wire\Tests\Fixtures\TestClassConstructor;
use Celema\Wire\Tests\Fixtures\TestClassDefault;
use Celema\Wire\Tests\Fixtures\TestClassInject;
use Celema\Wire\Tests\Fixtures\TestClassInjectCallback;
use Celema\Wire\Tests\Fixtures\TestClassMultiConstructor;
use Celema\Wire\Tests\Fixtures\TestClassObjectArgs;
use Celema\Wire\Tests\Fixtures\TestClassUsingNested;
use Celema\Wire\Tests\Fixtures\TestInterface;

final class CreatorTest extends TestCase
{
	public function testSimpleResolve(): void
	{
		$creator = new Creator($this->container());

		$this->assertInstanceOf(
			TestClassConstructor::class,
			$creator->create(TestClassConstructor::class),
		);
	}

	public function testResolveWithPartialArgs(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(TestClassMultiConstructor::class, [
			'name' => 'chuck',
			'number' => 73,
		]);

		$this->assertInstanceOf(TestClassMultiConstructor::class, $testobj);
		$this->assertSame('chuck', $testobj->name);
		$this->assertSame(73, $testobj->number);
		$this->assertInstanceOf(TestClass::class, $testobj->testobj);
	}

	public function testResolveWithPredefinedTypes(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(
			TestClassObjectArgs::class,
			predefinedArgs: ['test' => 'teststring'],
			predefinedTypes: [TestClass::class => new TestClass('predefined')],
		);

		$this->assertInstanceOf(TestClassObjectArgs::class, $testobj);
		$this->assertInstanceOf(TestClass::class, $testobj->testobj);
		$this->assertSame('teststring', $testobj->test);
		$this->assertSame('predefined', $testobj->testobj->str);
	}

	public function testResolveWithInjectCallback(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(
			TestClassInjectCallback::class,
			injectCallback: static fn(Inject $inject): mixed => $inject->value . ' ' . $inject->meta['id'],
		);

		$this->assertSame('callback injected id', $testobj->callback);
	}

	public function testResolveConstructorWithInjectCallback(): void
	{
		$creator = new Creator();
		$testobj = $creator->create(
			TestClassInjectCallback::class,
			constructor: 'create',
			injectCallback: static fn(Inject $inject): mixed => $inject->value . ' ' . $inject->meta['id'],
		);

		$this->assertSame('create callback injected id', $testobj->callback);
	}

	public function testResolveWithPartialArgsAndDefaultValues(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(TestClassDefault::class, ['number' => 73]);

		$this->assertInstanceOf(TestClassDefault::class, $testobj);
		$this->assertSame('default', $testobj->name);
		$this->assertSame(73, $testobj->number);
		$this->assertInstanceOf(TestClass::class, $testobj->testobj);
	}

	public function testResolveWithSimpleFactoryMethod(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(TestClassObjectArgs::class, constructor: 'fromDefaults');

		$this->assertSame(true, $testobj->testobj instanceof TestClass);
		$this->assertSame(true, $testobj->app instanceof TestClassApp);
		$this->assertSame('fromDefaults', $testobj->app->app());
		$this->assertSame('fromDefaults', $testobj->test);
	}

	public function testResolveWithFactoryMethodAndArgs(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(
			TestClassObjectArgs::class,
			['test' => 'passed', 'app' => 'passed'],
			constructor: 'fromArgs',
		);

		$this->assertSame(true, $testobj->testobj instanceof TestClass);
		$this->assertSame(true, $testobj->app instanceof TestClassApp);
		$this->assertSame('passed', $testobj->app->app());
		$this->assertSame('passed', $testobj->test);
	}

	public function testResolveWithNonAssocArgsArray(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->create(TestClassObjectArgs::class, [new TestClass('non assoc'), 'passed']);

		$this->assertSame(true, $testobj->testobj instanceof TestClass);
		$this->assertSame('non assoc', $testobj->testobj->str);
		$this->assertSame('passed', $testobj->test);
		$this->assertSame(true, $testobj->app instanceof TestClassApp);
	}

	public function testRejectsPositionalPredefinedArgsWhenInjectIsUsed(): void
	{
		$this->throws(WireException::class, 'predefined args must be named');

		$creator = new Creator($this->container());
		$creator->create(TestClassInject::class, ['positional']);
	}

	public function testResolveNestedClassesWithPredefinedAndInject(): void
	{
		$creator = new Creator($this->container());
		$tcun = $creator->create(
			TestClassUsingNested::class,
			injectCallback: static fn(Inject $inject): mixed => $inject->value . ' construct ' . $inject->meta['id'],
			predefinedTypes: ['string' => 'predefined-value'],
		);

		$this->assertSame('callback construct injected id', $tcun->tcn->callback);
		$this->assertSame('predefined-value', $tcun->tcn->predefined->value);
	}

	public function testResolveNestedClassesWithPredefinedInjectAndConstructor(): void
	{
		$creator = new Creator($this->container());
		$tcun = $creator->create(
			TestClassUsingNested::class,
			constructor: 'create',
			injectCallback: static fn(Inject $inject): mixed => $inject->value . ' create ' . $inject->meta['id'],
			predefinedTypes: ['string' => 'predefined-value'],
		);

		$this->assertSame('callback create injected id', $tcun->tcn->callback);
		$this->assertSame('predefined-value', $tcun->tcn->predefined->value);
	}

	public function testCreateNeverUsesTheContainerForTheRequestedClass(): void
	{
		$container = $this->container();
		$entry = new TestClass('entry');
		$container->add(TestClass::class, $entry);
		$creator = new Creator($container);
		$testobj = $creator->create(TestClass::class);

		$this->assertNotSame($entry, $testobj);
		$this->assertSame('', $testobj->str);
	}

	public function testCreateRejectsTypesThatCannotBeInstantiated(): void
	{
		$this->throws(
			WireException::class,
			'Unresolvable: Celema\\Wire\\Tests\\Fixtures\\TestInterface cannot be instantiated',
		);

		$container = $this->container();
		$container->add(TestInterface::class, new TestClass('text'));
		new Creator($container)->create(TestInterface::class);
	}

	public function testResolveReturnsTheContainerEntry(): void
	{
		$container = $this->container();
		$container->add(TestInterface::class, new TestClass('text'));
		$creator = new Creator($container);
		$testobj = $creator->resolve(TestInterface::class);

		$this->assertInstanceof(TestClass::class, $testobj);
		$this->assertSame('text', $testobj->str);
	}

	public function testResolveDoesNotApplyCallAttributesToContainerEntries(): void
	{
		$container = $this->container();
		$entry = new TestClassCallCounter();
		$container->add(TestClassCallCounter::class, $entry);
		$creator = new Creator($container);
		$first = $creator->resolve(TestClassCallCounter::class);
		$second = $creator->resolve(TestClassCallCounter::class);

		$this->assertSame($entry, $first);
		$this->assertSame($first, $second);
		$this->assertSame(0, $entry->calls);
	}

	public function testResolveCreatesUnregisteredClasses(): void
	{
		$creator = new Creator($this->container());
		$testobj = $creator->resolve(
			TestClassObjectArgs::class,
			predefinedTypes: [
				TestClass::class => new TestClass('predefined'),
				'string' => 'teststring',
			],
		);
		$counter = $creator->resolve(TestClassCallCounter::class);

		$this->assertInstanceOf(TestClassObjectArgs::class, $testobj);
		$this->assertSame('predefined', $testobj->testobj->str);
		$this->assertSame(1, $counter->calls);
	}

	public function testResolveWithoutContainerCreates(): void
	{
		$testobj = new Creator()->resolve(TestClass::class);

		$this->assertInstanceOf(TestClass::class, $testobj);
	}

	public function testResolveRejectsUnknownIds(): void
	{
		$this->throws(WireException::class, 'Unresolvable: missing-entry is neither a container entry nor a class');

		new Creator($this->container())->resolve('missing-entry');
	}

	public function testResolveRejectsEntriesThatAreNoObjects(): void
	{
		$this->throws(WireException::class, 'Unresolvable: container entry config is not an object');

		$container = $this->container();
		$container->add('config', ['debug' => true]);
		new Creator($container)->resolve('config');
	}

	public function testResolveSharesAutowiredContainerEntriesAcrossScopes(): void
	{
		$root = $this->scopedContainer();
		$root->add(TestClassCallCounter::class, TestClassCallCounter::class);
		$scope1 = $root->scope();
		$scope2 = $root->scope();
		$creator1 = new Creator($scope1);
		$creator2 = new Creator($scope2);
		$instance = $creator1->resolve(TestClassCallCounter::class);

		$this->assertInstanceOf(TestClassCallCounter::class, $instance);
		$this->assertSame($instance, $creator1->resolve(TestClassCallCounter::class));
		$this->assertSame($instance, $creator2->resolve(TestClassCallCounter::class));
		$this->assertSame($instance, $root->get(TestClassCallCounter::class));
		$this->assertSame(1, $instance->calls);

		$created = $creator1->create(TestClassCallCounter::class);

		$this->assertNotSame($instance, $created);
		$this->assertSame(1, $created->calls);
		$this->assertSame($instance, $creator1->resolve(TestClassCallCounter::class));
	}

	public function testResolveScopesAutowiredContainerEntriesAndTheirDependencies(): void
	{
		$root = $this->scopedContainer();
		$root->add(TestClassConstructor::class, TestClassConstructor::class, $root::SCOPED);
		$scope1 = $root->scope();
		$scope2 = $root->scope();
		$dependency1 = new TestClass('scope1');
		$dependency2 = new TestClass('scope2');
		$scope1->add(TestClass::class, $dependency1);
		$scope2->add(TestClass::class, $dependency2);
		$creator1 = new Creator($scope1);
		$creator2 = new Creator($scope2);
		$instance1 = $creator1->resolve(TestClassConstructor::class);
		$instance2 = $creator2->resolve(TestClassConstructor::class);

		$this->assertInstanceOf(TestClassConstructor::class, $instance1);
		$this->assertInstanceOf(TestClassConstructor::class, $instance2);
		$this->assertSame($instance1, $creator1->resolve(TestClassConstructor::class));
		$this->assertSame($instance2, $creator2->resolve(TestClassConstructor::class));
		$this->assertNotSame($instance1, $instance2);
		$this->assertSame($dependency1, $instance1->testobj);
		$this->assertSame($dependency2, $instance2->testobj);
	}

	public function testResolveHonorsSharedLifetimeAcrossScopes(): void
	{
		$root = $this->scopedContainer();
		$root->add(TestClass::class, static fn() => new TestClass('shared'));
		$scope1 = $root->scope();
		$scope2 = $root->scope();
		$creator1 = new Creator($scope1);
		$creator2 = new Creator($scope2);
		$instance11 = $creator1->resolve(TestClass::class);
		$instance12 = $creator1->resolve(TestClass::class);
		$instance2 = $creator2->resolve(TestClass::class);

		$this->assertSame($instance11, $instance12);
		$this->assertSame($instance11, $instance2);
	}

	public function testResolveHonorsScopedLifetime(): void
	{
		$root = $this->scopedContainer();
		$root->add(TestClass::class, static fn() => new TestClass('scoped'), $root::SCOPED);
		$scope1 = $root->scope();
		$scope2 = $root->scope();
		$creator1 = new Creator($scope1);
		$creator2 = new Creator($scope2);
		$instance11 = $creator1->resolve(TestClass::class);
		$instance12 = $creator1->resolve(TestClass::class);
		$instance2 = $creator2->resolve(TestClass::class);

		$this->assertSame($instance11, $instance12);
		$this->assertNotSame($instance11, $instance2);
	}
}
