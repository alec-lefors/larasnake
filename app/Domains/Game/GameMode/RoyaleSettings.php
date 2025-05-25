<?php

declare(strict_types=1);

namespace App\Domains\Game\GameMode;

use Spatie\LaravelData\Data;

class RoyaleSettings extends Data
{
    public function __construct(
        public int $shrinkEveryNTurns,
    ) {
    }
}
