<?php

declare(strict_types=1);

namespace App\Domains\Move;

use App\Domains\Board\BoardData;
use App\Domains\Game\GameData;
use App\Domains\Snake\BattlesnakeData;
use Spatie\LaravelData\Data;

class MoveRequest extends Data
{
    public function __construct(
        public GameData $game,
        public int $turn,
        public BoardData $board,
        public BattlesnakeData $you,
    ) {
    }
}
