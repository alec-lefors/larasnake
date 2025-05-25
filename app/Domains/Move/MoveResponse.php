<?php

declare(strict_types=1);

namespace App\Domains\Move;

use App\Domains\Snake\Shout;

readonly class MoveResponse
{
    public function __construct(
        public Direction $move = Direction::UP,
        public ?Shout $shout = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'move' => $this->move,
            ...($this->shout ?? ['shout' => $this->shout]),
        ];
    }
}
