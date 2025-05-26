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

class MoveController extends Controller
{
    public function __construct(
        public ?Shout $shout = null,
    ) {
    }

    public function __invoke(MoveRequest $data): JsonResponse
    {
        $edibleFood = Arr::reject(
            $data->board->food,
            fn(Coordinates $coordinates) => $coordinates->getDistanceTo($data->you->head) > 6
        );
        $huntFood = new HuntFood($data->you->head, $edibleFood);
        $edibleSnakes = $data->board->getEdibleSnakesBy($data->you);
        $huntSnakes = new HuntSnake($data->you, $edibleSnakes);

        $data->board->snakes = Arr::map(
            $data->board->snakes,
            function (BattlesnakeData $snake) use ($data) {
                if ($data->you->length > $snake->length) {
                    $snake->edible = true;
                }
                return $snake;
            }
        );

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
