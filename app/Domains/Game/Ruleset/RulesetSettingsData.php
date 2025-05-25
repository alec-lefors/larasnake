<?php

declare(strict_types=1);

namespace App\Domains\Game\Ruleset;

use App\Domains\Game\GameMode\RoyaleSettings;
use App\Domains\Game\GameMode\SquadSettings;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class RulesetSettingsData extends Data
{
    public function __construct(
        public int $foodSpawnChance,
        public int $minimumFood,
        public int $hazardDamagePerTurn,
        public Optional|RoyaleSettings $royale,
        public Optional|SquadSettings $squad,
    ) {
    }
}
