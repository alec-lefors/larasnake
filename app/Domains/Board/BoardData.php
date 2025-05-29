<?php

declare(strict_types=1);

namespace App\Domains\Board;

use App\Domains\Move\Direction;
use App\Domains\Snake\BattlesnakeData;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCastable;
use Spatie\LaravelData\Data;

class BoardData extends Data
{
    private array $matrix;

    /**
     * @param int               $height
     * @param int               $width
     * @param Coordinates[]     $food
     * @param Coordinates[]     $hazards
     * @param BattlesnakeData[] $snakes
     */
    public function __construct(
        public int $height,
        public int $width,
        #[WithCastable(Coordinates::class, spaceType: SpaceType::FOOD)]
        public array $food,
        #[WithCastable(Coordinates::class, spaceType: SpaceType::HAZARD)]
        public array $hazards,
        public array $snakes,
    ) {
        $this->matrix = [];

        for ($i = 0; $i < $this->width; $i++) {
            for ($j = 0; $j < $this->height; $j++) {
                $this->matrix[$i][$j] = new Coordinates(
                    x: $i,
                    y: $j,
                    spaceType: SpaceType::EMPTY
                );
            }
        }

        foreach ($this->food as $space) {
            $this->matrix[$space->x][$space->y] = $space;
        }

        foreach ($this->hazards as $space) {
            $this->matrix[$space->x][$space->y] = $space;
        }

        foreach ($this->snakes as $snake) {
            foreach ($snake->body as $space) {
                $this->matrix[$space->x][$space->y] = $space;
            }

            $this->matrix[$snake->head->x][$snake->head->y] = $snake->head;
        }
    }

    public function getMatrix(): array
    {
        return $this->matrix;
    }

    public function isSpaceOccupied(Coordinates $coordinates): bool
    {
        if (isset($this->matrix[$coordinates->x][$coordinates->y])) {
            $space = $this->matrix[$coordinates->x][$coordinates->y];
            if ($space instanceof Coordinates) {
                return $space->spaceType->value > SpaceType::EMPTY->value;
            }
        }

        return true;
    }

    public function getValidAdjacentCoordinatesFrom(BattlesnakeData $snake): array
    {
        $validCoordinates = [];
        $coordinates = $snake->head;

        foreach (Direction::cases() as $direction) {
            // Get the coordinate from a direction relative to the head.
            $space = $this->getCoordinatesFromDirection($coordinates, $direction);

            // If the snake fits, the move is valid
            if ($count = $this->spacesSnakeCanTravelFrom($snake, $space)) {
                $validCoordinates[] = [$count, $space];
            }
        }

        return Arr::pluck(
            Arr::sortDesc(
                $validCoordinates,
                fn(array $item) => $item[0]
            ),
            1
        );
    }

    public function getCoordinatesFromDirection(Coordinates $startingPosition, Direction $direction): Coordinates
    {
        $coords = $direction->getCoordinates($startingPosition);

        return $this->matrix[$coords->x][$coords->y] ?? $coords;
    }

    /**
     * @param BattlesnakeData $by     Snake to make size comparisons against.
     * @param int             $offset By how much more should edible snakes be.
     *
     * @return BattlesnakeData[]
     */
    public function assignEdibleSnakes(BattlesnakeData $by, int $offset = 0): array
    {
        return Arr::map(
            $this->snakes,
            function (BattlesnakeData $snake) use ($by, $offset) {
                $edible = true;

                if ($snake->id === $by->id) {
                    $edible = false;
                }

                if ($by->squad !== null && $snake->squad === $by->squad) {
                    $edible = false;
                }

                if ($snake->length >= $by->length + $offset) {
                    $edible = false;
                }

                $snake->edible = $edible;

                return $snake;
            }
        );
    }

    public function spacesSnakeCanTravelFrom(BattlesnakeData $snake, Coordinates $pos): int
    {
        // This space is blocked, immediate return.
        if ($pos->spaceType->value > SpaceType::EMPTY) {
            return 0;
        }

        $validPositions = [[$pos->x, $pos->y]];
        $queue = [[$pos->x, $pos->y]];
        $count = 1;

        while (count($queue) > 0) {
            [$x, $y] = array_shift($queue);

            foreach (Direction::cases() as $direction) {
                $nextPos = $this->getCoordinatesFromDirection(new Coordinates($x, $y), $direction);

                if ($count > $snake->length) {
                    $count++;
                    break;
                }

                if (in_array([$nextPos->x, $nextPos->y], $validPositions)) {
                    continue;
                }

                if ($nextPos->spaceType->value > SpaceType::EMPTY->value) {
                    continue;
                }

                $count++;
                $queue[] = [$nextPos->x, $nextPos->y];
                $validPositions = [$nextPos->x, $nextPos->y];
            }
        }

        return $count;
    }
}
