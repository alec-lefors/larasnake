<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Board\Coordinates;
use App\Domains\Move\Directives\FirstBest;
use App\Domains\Move\Directives\HuntFood;
use App\Domains\Move\Directives\HuntSnake;
use App\Domains\Move\Directives\Random;
use App\Domains\Move\MoveResponse;
use App\Domains\Move\MoveRequest;
use App\Domains\Move\SnakeLogic;
use App\Domains\Snake\BattlesnakeData;
use App\Domains\Snake\Shout;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use JMGQ\AStar\AStar;
use Laravel\Octane\Exceptions\TaskException;
use Laravel\Octane\Exceptions\TaskTimeoutException;
use Laravel\Octane\Facades\Octane;

class MoveController extends Controller
{
    public function __construct(
        public ?Shout $shout = null,
    ) {
    }

    /**
     * @throws TaskTimeoutException
     * @throws TaskException
     */
    public function __invoke(MoveRequest $data): JsonResponse
    {
        [$edibleFood, $snakes] = Octane::concurrently(
            [
                Arr::reject(
                    $data->board->food,
                    fn(Coordinates $coordinates) => $coordinates->getDistanceTo($data->you->head) > 6
                ),
                $data->board->assignEdibleSnakes($data->you, 1)
            ]
        );

        $huntFood = new HuntFood($data->you->head, $edibleFood);
        $huntSnakes = new HuntSnake($data->you, Arr::where($snakes, fn(BattlesnakeData $snake) => $snake->edible));

        $data->board->snakes = $snakes;

        $snakeLogic = new SnakeLogic($data->board, $data->you);
        $pathGenerator = new AStar($snakeLogic);

        $validCoordinates = $data->board->getValidAdjacentCoordinatesFrom($data->you);
        $fallbackMove = new FirstBest($data->you->head, $validCoordinates)->getTarget();


        $target = match (true) {
            !empty($edibleSnakes) => $huntSnakes->getTarget(),
            !empty($edibleFood) => $huntFood->getTarget(),
            default => $fallbackMove
        };

        $path = $pathGenerator->run($data->you->head, $target);

        if (!isset($path[1]) || !in_array($path[1], $validCoordinates)) {
            $target = $fallbackMove;
            $this->shout = new Shout("Oh no...");
            Log::info(json_encode(request()->toArray()));
        } else {
            $target = $path[1];
        }

        $direction = $data->you->head->directionTo($target);

        return response()->json(
            new MoveResponse(
                move: $direction,
                shout: $this->shout,
            )
        );
    }
}
