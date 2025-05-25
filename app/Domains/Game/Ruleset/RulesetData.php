<?php

declare(strict_types=1);

namespace App\Domains\Game\Ruleset;

use Spatie\LaravelData\Data;

class RulesetData extends Data
{
    public function __construct(
        public string $name,
        public string $version,
        public RulesetSettingsData $settings,
    ) {
    }
}
