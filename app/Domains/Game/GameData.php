<?php

declare(strict_types=1);

namespace App\Domains\Game;

use Spatie\LaravelData\Data;

class GameData extends Data
{
    public function __construct(
        public string $id,
        public array $ruleset,
        public string $map,
        public int $timeout,
        public string $source,
    ) {
    }
}
