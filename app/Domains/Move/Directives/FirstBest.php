<?php

declare(strict_types=1);

namespace App\Domains\Move\Directives;

use App\Domains\Board\Coordinates;
use App\Domains\Move\Direction;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class FirstBest implements Directive
{
    /**
     * @param array<Coordinates> $validCoordinates
     */
    public function __construct(
        public Coordinates $startingPosition,
        public array $validCoordinates,
    ) {
    }

    public function getTarget(): Coordinates
    {
        if (isset($this->validCoordinates[0])) {
            return $this->validCoordinates[0];
        }

        return Direction::UP->getCoordinates($this->startingPosition);
    }
}
