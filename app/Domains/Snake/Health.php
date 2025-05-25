<?php

declare(strict_types=1);

namespace App\Domains\Snake;

use InvalidArgumentException;
use Spatie\LaravelData\Data;

class Health extends Data
{
    public function __construct(
        public int $value,
    ) {
        if ($value < 0 || $value > 100) {
            throw new InvalidArgumentException("Value: $this->value must be between 0 and 100");
        }
    }
}
