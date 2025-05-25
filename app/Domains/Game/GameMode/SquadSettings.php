<?php

declare(strict_types=1);

namespace App\Domains\Game\GameMode;

use Spatie\LaravelData\Data;

class SquadSettings extends Data
{
    public function __construct(
        public bool $allowBodyCollisions,
        public bool $sharedElimination,
        public bool $sharedHealth,
        public bool $sharedLength,
    ) {
    }
}
