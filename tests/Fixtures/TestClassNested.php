<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

use Celemas\Wire\Inject;
use Celemas\Wire\Type;

class TestClassNested
{
	public function __construct(
		#[Inject('callback', Type::Callback, id: 'injected id')]
		public readonly string $callback,
		public readonly TestClassPredefined $predefined,
	) {}
}
