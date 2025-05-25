<?php

declare(strict_types=1);

namespace App\Domains\Snake\Configuration;

use InvalidArgumentException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

readonly class HexColor implements Castable
{
    public function __construct(
        public string $hexColor,
    ) {
        if (strlen($this->hexColor) !== 7) {
            throw new InvalidArgumentException("\$hexColor: $this->hexColor must be 7 characters.");
        }

        if (!str_starts_with($this->hexColor, '#')) {
            throw new InvalidArgumentException("\$hexColor: $this->hexColor must begin with a # symbol.");
        }

        $hexCode = substr($this->hexColor, -6);

        if (!ctype_xdigit($hexCode)) {
            throw new InvalidArgumentException(
                "\$hexColor: $this->hexColor does not contain valid hexadecimal numbers.",
            );
        }
    }

    public function __toString(): string
    {
        return $this->hexColor;
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
                return new HexColor($value);
            }
        };
    }
}
