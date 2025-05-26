<?php

declare(strict_types=1);

namespace App\Domains\Move\Directives;

use App\Domains\Board\Coordinates;

interface Directive
{
    public function getTarget(): Coordinates;
}
