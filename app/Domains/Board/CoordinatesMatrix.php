<?php

declare(strict_types=1);

namespace App\Domains\Board;

class CoordinatesMatrix
{
    public array $matrix;

    /**
     * @param array<Coordinates> $coordinates
     */
    public function __construct(
        public readonly array $coordinates,
        public readonly int $width,
        public readonly int $height,
    ) {
        for ($i = 0; $i < $this->width; $i++) {
            for ($j = 0; $j < $this->height; $j++) {
                $this->matrix[$i][$j] = false;
            }
        }

        foreach ($coordinates as $coordinate) {
            if (!$coordinate instanceof Coordinates) {
                throw new \InvalidArgumentException("Coordinates must be instance of Coordinate");
            }

            if ($coordinate->x < 0 || $coordinate->y < 0) {
                throw new \InvalidArgumentException("Coordinates x and y must be greater than 0");
            }

            if ($coordinate->x > $this->width || $coordinate->y > $this->height) {
                throw new \InvalidArgumentException("Coordinates x and y must be less than the board size");
            }

            $this->matrix[$coordinate->x][$coordinate->y] = true;
        }
    }

    public function isSpaceOccupied(Coordinates $coordinates): bool
    {
        if (isset($this->matrix[$coordinates->x][$coordinates->y])) {
            return $this->matrix[$coordinates->x][$coordinates->y];
        }

        return true;
    }
}
