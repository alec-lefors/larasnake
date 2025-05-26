<?php

declare(strict_types=1);

namespace App\Domains\Move\Directives;

use App\Domains\Board\Coordinates;

class HuntFood implements Directive
{

    /**
     * @param Coordinates        $startingPosition
     * @param array<Coordinates> $food
     */
    public function __construct(
        public Coordinates $startingPosition,
        public array $food,
    ) {
    }

    public function getTarget(): Coordinates
    {
        return $this->startingPosition->getClosestCoordinates($this->food);
    }
}
