<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Celema\Wire\CallableResolver;
use Celema\Wire\ConstructorResolver;
use Celema\Wire\Creator;
// A PSR-11 container implementation like
// https://celema.dev/registry or https://php-di.org
use Celema\Wire\Tests\Fixtures\Container;

$container = new Container();
$creator = new Creator($container);
$callableresolver = new CallableResolver($creator);
$constructorResolver = new ConstructorResolver($creator);

// Or without container
$creator = new Creator();
$callableresolver = new CallableResolver($creator);
$constructorResolver = new ConstructorResolver($creator);
