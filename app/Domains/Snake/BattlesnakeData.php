<?php

declare(strict_types=1);

namespace App\Domains\Snake;

use App\Domains\Board\Coordinates;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

class BattlesnakeData extends Data
{
    /**
     * @param string             $id             The ID of the Battlesnake
     * @param string             $name           The name of the Battlesnake
     * @param Health             $health         The health of the Battlesnake ranging from 0 to 100.
     * @param array<Coordinates> $body           Array of coordinates representing this Battlesnake's location on the
     *                                           game board.
     * @param string|null        $latency        The previous response time of this Battlesnake, in milliseconds.
     * @param Coordinates        $head           Coordinates for this Battlesnake's head.
     * @param int                $length         Length of this Battlesnake from head to tail.
     * @param Shout|null         $shout          Message shouted by this Battlesnake on the previous turn.
     * @param string|null        $squad     The squad that the Battlesnake belongs to.
     * @param Customizations     $customizations The collection of customizations that control how this Battlesnake is
     *                                           displayed
     */
    public function __construct(
        public string $id,
        public string $name,
        #[WithCastable(Health::class)]
        public Health $health,
        #[DataCollectionOf(Coordinates::class)]
        public array $body,
        public string|null $latency,
        public Coordinates $head,
        public int $length,
        #[WithCastable(Shout::class)]
        public Shout|null $shout,
        public string|null $squad,
        public Customizations $customizations,
    ) {
    }
}
