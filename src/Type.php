<?php

declare(strict_types=1);

namespace Celemas\Wire;

enum Type
{
	case Literal;
	case Env;
	case Create;
	case Entry;
	case Callback;
}
