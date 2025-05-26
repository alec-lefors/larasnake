<?php

declare(strict_types=1);

namespace App\Domains\Move;

use App\Domains\Board\Coordinates;
use App\Domains\Board\SpaceType;
use InvalidArgumentException;

enum Direction: string
{
    case UP = 'up';
    case DOWN = 'down';
    case LEFT = 'left';
    case RIGHT = 'right';

    public function getCoordinates(Coordinates $startingCoords): Coordinates
    {
        [$x, $y] = match ($this) {
            self::UP => [$startingCoords->x, $startingCoords->y + 1],
            self::DOWN => [$startingCoords->x, $startingCoords->y - 1],
            self::LEFT => [$startingCoords->x - 1, $startingCoords->y],
            self::RIGHT => [$startingCoords->x + 1, $startingCoords->y],
        };
        
        return new Coordinates($x, $y, SpaceType::OUT_OF_BOUNDS);
    }
}
