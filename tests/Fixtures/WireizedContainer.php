<?php

declare(strict_types=1);

namespace Celemas\Wire\Tests\Fixtures;

use Celemas\Wire\WireContainer;

class WireizedContainer extends Container implements WireContainer
{
	public function definition(string $id): mixed
	{
		return $this->entries[$id];
	}
}
