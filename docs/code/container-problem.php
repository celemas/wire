<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Celema\Wire\Exception\WireException;
use Celema\Wire\Wire;

class Value
{
	public function __construct(protected string $str) {}
}

class Model
{
	public function __construct(protected Value $value) {}
}

$creator = Wire::creator();

try {
	$creator->create(Model::class);
} catch (WireException $e) {
	$message = $e->getMessage();
} finally {
	assert(str_contains($message, 'Unresolvable parameter'));
}
