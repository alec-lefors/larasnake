<?php

declare(strict_types=1);

namespace App\Domains\Board;

class CoordinatesMatrix
{
    public array $matrix;

    /**
     * @param array<Coordinates> $coordinates
     */
    public function __construct(public readonly array $coordinates)
    {
        foreach ($coordinates as $coordinate) {
            $this->matrix[$coordinate->x][$coordinate->y] = true;
        }
    }

    public function spaceIsEmpty(Coordinates $coordinates): bool
    {
        return !isset($this->matrix[$coordinates->x][$coordinates->y]);
    }

    public function spaceIsOccupied(Coordinates $coordinates): bool
    {
        return isset($this->matrix[$coordinates->x][$coordinates->y]);
    }
}
