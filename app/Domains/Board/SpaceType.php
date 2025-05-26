<?php

declare(strict_types=1);

namespace App\Domains\Board;

enum SpaceType: int
{
    case FOOD = -1;
    case EMPTY = 0;
    case SNAKE_HEAD = 1;
    case HAZARD = 5;
    case SNAKE_BODY = 10;
    case OUT_OF_BOUNDS = 20;
}
