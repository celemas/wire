<?php

declare(strict_types=1);

namespace Celema\Wire;

enum Type
{
	case Literal;
	case Env;
	case Create;
	case Entry;
	case Callback;
}
