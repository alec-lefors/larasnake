<?php

declare(strict_types=1);

namespace App\Domains\Snake;

use InvalidArgumentException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

readonly class Shout implements Castable
{
    public function __construct(
        public string $message,
    ) {
        if (strlen($this->message) > 256) {
            throw new InvalidArgumentException('Shout message is too long.');
        }
    }

    public function __toString(): string
    {
        return $this->message;
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
                return new Shout($value);
            }
        };
    }
}
