<?php

declare(strict_types=1);

namespace App\Domains\Board;

use Spatie\LaravelData\Data;

class Coordinates extends Data
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }
}
