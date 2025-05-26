<?php

declare(strict_types=1);

namespace App\Domains\Move;

use App\Domains\Board\BoardData;
use App\Domains\Board\Coordinates;
use App\Domains\Board\SpaceType;
use App\Domains\Snake\BattlesnakeData;
use Illuminate\Support\Arr;
use JMGQ\AStar\DomainLogicInterface;

class SnakeLogic implements DomainLogicInterface
{

    public function __construct(public BoardData $board, public BattlesnakeData $you)
    {
    }

    /**
     * @param Coordinates $node
     *
     * @return array<Coordinates>
     */
    public function getAdjacentNodes(mixed $node): iterable
    {
        $adjacentNodes = [];

        foreach (Direction::cases() as $direction) {
            // Get the coordinate from a direction relative to the head.
            $adjacentNodes[] = $this->board->getCoordinatesFromDirection($node, $direction);

            // If that coordinate space is empty, then add it to the valid directions.
//            if (!$this->board->isSpaceOccupied($space)) {
//                $adjacentNodes[] = $space;
//            }
        }

        return $adjacentNodes;
    }

    /**
     * @param Coordinates $node
     * @param Coordinates $adjacent
     */
    public function calculateRealCost(mixed $node, mixed $adjacent): float|int
    {
        $cost = 0;

        if ($node->isAdjacentTo($adjacent)) {
            if ($adjacent->spaceType === SpaceType::SNAKE_HEAD) {
                $enemy = Arr::first(
                    $this->board->snakes,
                    fn(BattlesnakeData $snake) => $snake->id === $adjacent->snakeId
                );

                $cost += $enemy->edible ? SpaceType::FOOD->value : SpaceType::SNAKE_BODY->value;
            } else {
                $cost = $adjacent->spaceType->value;
            }
        } else {
            $cost = SpaceType::OUT_OF_BOUNDS->value;
        }

        return $cost;
    }

    /**
     * @param Coordinates $fromNode
     * @param Coordinates $toNode
     */
    public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): float|int
    {
        $rowFactor = ($fromNode->x - $toNode->x) ** 2;
        $columnFactor = ($fromNode->y - $toNode->y) ** 2;

        return sqrt($rowFactor + $columnFactor);
    }
}
