<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Celema\Wire\Tests\Fixtures\Container;
use Celema\Wire\Wire;

class Mailer
{
	public function __construct(public readonly string $dsn = 'smtp://localhost') {}
}

class Newsletter
{
	public function __construct(public readonly Mailer $mailer) {}
}

$mailer = new Mailer('smtp://mail.example.com');
$container = new Container();
$container->add(Mailer::class, $mailer);

$creator = Wire::creator($container);

// resolve() returns the registered entry
assert($creator->resolve(Mailer::class) === $mailer);

// create() always builds a new object, but resolves its parameters through the container
assert($creator->create(Mailer::class) !== $mailer);
assert($creator->create(Newsletter::class)->mailer === $mailer);

// Unregistered classes are created by both methods
assert($creator->resolve(Newsletter::class) instanceof Newsletter);
