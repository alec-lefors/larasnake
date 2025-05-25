<?php

declare(strict_types=1);

namespace App\Domains\Move;

use App\Domains\Board\Coordinates;

enum Direction: string
{
    case UP = 'up';
    case DOWN = 'down';
    case LEFT = 'left';
    case RIGHT = 'right';

    public function getCoordinates(Coordinates $startingCoords): Coordinates
    {
        return match ($this) {
            self::UP => new Coordinates($startingCoords->x, $startingCoords->y + 1),
            self::DOWN => new Coordinates($startingCoords->x, $startingCoords->y - 1),
            self::LEFT => new Coordinates($startingCoords->x - 1, $startingCoords->y),
            self::RIGHT => new Coordinates($startingCoords->x + 1, $startingCoords->y),
        };
    }
}
