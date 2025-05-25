<?php

declare(strict_types=1);

namespace App\Domains\Snake;

use InvalidArgumentException;
use Spatie\LaravelData\Data;

class Shout extends Data
{
    public function __construct(
        public string $message,
    ) {
        if (strlen($this->message) > 256) {
            throw new InvalidArgumentException('Shout message is too long.');
        }
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
