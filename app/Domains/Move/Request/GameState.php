<?php

declare(strict_types=1);

namespace App\Domains\Move\Request;

use App\Domains\Board\BoardData;
use App\Domains\Snake\BattlesnakeData;
use Spatie\LaravelData\Data;

class GameState extends Data
{
    public function __construct(
        public GameData $gameData,
        public int $turn,
        public BoardData $board,
        public BattlesnakeData $you,
    ) {
    }
}
