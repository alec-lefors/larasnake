<?php

declare(strict_types=1);

namespace App\Domains\Move\Directives;

use App\Domains\Board\Coordinates;
use App\Domains\Snake\BattlesnakeData;
use Illuminate\Support\Arr;

class HuntSnake implements Directive
{

    /**
     * @param BattlesnakeData        $you
     * @param array<BattlesnakeData> $edibleSnakes
     */
    public function __construct(
        public BattlesnakeData $you,
        public array $edibleSnakes,
    ) {
    }

    public function getTarget(): Coordinates
    {
        $enemySnakeHeads = Arr::map($this->edibleSnakes, fn(BattlesnakeData $snake) => $snake->head);
        return $this->you->head->getClosestCoordinates($enemySnakeHeads);
    }
}
