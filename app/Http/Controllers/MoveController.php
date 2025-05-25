<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Move\Direction;
use App\Domains\Move\MoveResponse;
use App\Domains\Move\MoveRequest;
use App\Domains\Move\ValidMoves;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class MoveController extends Controller
{
    public function __invoke(MoveRequest $data): JsonResponse
    {
        $validMoves = new ValidMoves($data->board, $data->you->head);

        try {
            $direction = Arr::random($validMoves->generate());
        } catch (InvalidArgumentException $e) {
            $direction = Arr::random(Direction::cases());
        }

        return response()->json(
            new MoveResponse(
                move: $direction,
                shout: null,
            )
        );
    }
}
