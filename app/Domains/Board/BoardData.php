<?php

declare(strict_types=1);

namespace App\Domains\Board;

use App\Domains\Snake\BattlesnakeData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class BoardData extends Data
{
    /**
     * @param int                    $height
     * @param int                    $width
     * @param array<Coordinates>     $food
     * @param array<Coordinates>     $hazards
     * @param array<BattlesnakeData> $snakes
     */
    public function __construct(
        public int $height,
        public int $width,
        #[DataCollectionOf(Coordinates::class)]
        public array $food,
        #[DataCollectionOf(Coordinates::class)]
        public array $hazards,
        #[DataCollectionOf(BattlesnakeData::class)]
        public array $snakes,
    ) {
    }
}
