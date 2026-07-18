<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use Celema\Wire\Inject;
use Celema\Wire\Type;

class TestClassNested
{
	public function __construct(
		#[Inject('callback', Type::Callback, id: 'injected id')]
		public readonly string $callback,
		public readonly TestClassPredefined $predefined,
	) {}
}
