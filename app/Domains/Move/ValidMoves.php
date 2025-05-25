<?php

declare(strict_types=1);

namespace App\Domains\Move;

use App\Domains\Board\BoardData;
use App\Domains\Board\Coordinates;
use App\Domains\Board\CoordinatesMatrix;
use App\Domains\Snake\BattlesnakeData;

readonly class ValidMoves
{
    public CoordinatesMatrix $invalidSpaces;

    public function __construct(
        public BoardData $board,
        public Coordinates $head,
    ) {
        $invalidSpaces = [];

        foreach ($board->snakes as $snake) {
            $invalidSpaces[] = [
                ...$snake->body,
            ];
        }

        $invalidSpaces[] = [
            ...$this->board->hazards,
        ];

        $this->invalidSpaces = new CoordinatesMatrix($invalidSpaces);
    }

    /**
     * @return array<Direction>
     */
    public function generate(): array
    {
        $validDirections = [];

        foreach (Direction::cases() as $direction) {
            // Get the coordinate from a direction relative to the head.
            $space = $direction->getCoordinates($this->head);

            // If that coordinate space is empty, then add it to the valid directions.
            if ($this->invalidSpaces->spaceIsEmpty($space)) {
                $validDirections[] = $direction;
            }
        }

        return $validDirections;
    }
}
