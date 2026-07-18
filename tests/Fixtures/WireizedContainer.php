<?php

declare(strict_types=1);

namespace Celema\Wire\Tests\Fixtures;

use Celema\Wire\WireContainer;

class WireizedContainer extends Container implements WireContainer
{
	public function definition(string $id): mixed
	{
		return $this->entries[$id];
	}
}
