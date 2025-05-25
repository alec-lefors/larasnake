<?php

declare(strict_types=1);

namespace App\Domains\Snake;

use InvalidArgumentException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

readonly class Health implements Castable
{
    public function __construct(
        public int $value,
    ) {
        if ($value < 0 || $value > 100) {
            throw new InvalidArgumentException("Value: $this->value must be between 0 and 100");
        }
    }

    public static function dataCastUsing(...$arguments): Cast
    {
        return new class implements Cast {
            public function cast(
                DataProperty $property,
                mixed $value,
                array $properties,
                CreationContext $context
            ): mixed {
                return new Health($value);
            }
        };
    }
}
