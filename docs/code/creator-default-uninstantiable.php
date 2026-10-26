<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Celema\Wire\Wire;

enum Format
{
	case Html;
	case Text;
}

class Mailer
{
	public function __construct(
		public ?Closure $transport = null,
		public Format $format = Format::Html,
	) {}
}

$creator = Wire::creator();
$mailer = $creator->create(Mailer::class);

assert($mailer->transport === null);
assert($mailer->format === Format::Html);
