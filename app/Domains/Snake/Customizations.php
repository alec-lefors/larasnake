<?php

declare(strict_types=1);

namespace App\Domains\Snake;

use App\Domains\Snake\Configuration\HexColor;
use Spatie\LaravelData\Data;

class Customizations extends Data
{
    public function __construct(
        public HexColor $color,
        public string $head,
        public string $tail,
    ) {
    }
}
