<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Move\Direction;
use App\Domains\Move\MoveResponse;
use App\Domains\Move\Request\GameState;
use App\Domains\Move\ValidMoves;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MoveController extends Controller
{
    public function __invoke(GameState $data): JsonResponse
    {
        $validMoves = new ValidMoves($data->board, $data->you->head);

        $direction = Arr::random($validMoves->generate());

        return response()->json(
            new MoveResponse(
                move: $direction,
                shout: null,
            ),
        );
    }
}
