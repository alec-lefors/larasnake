<?php

declare(strict_types=1);

namespace App\Domains\Board;

use App\Domains\Move\Direction;
use Illuminate\Support\Optional;
use JMGQ\AStar\Node\NodeIdentifierInterface;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Castable;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class Coordinates extends Data implements Castable, NodeIdentifierInterface
{
    public function __construct(
        public int $x,
        public int $y,
        public Optional|SpaceType|null $spaceType = null,
        public Optional|string|null $snakeId = null,
    ) {
    }

    public static function fromString(string $coordinates): self
    {
        [$x, $y, $spaceType, $snakeId] = explode(',', $coordinates);
        return new self(
            x: (int)$x,
            y: (int)$y,
            spaceType: SpaceType::from((int)$spaceType),
            snakeId: $snakeId,
        );
    }

    public function __toString(): string
    {
        return "$this->x,$this->y,{$this->spaceType?->value},$this->snakeId";
    }


    public function isAdjacentTo(Coordinates $other): bool
    {
        if ($this->x === $other->x && $this->y === $other->y) {
            return false;
        }

        return abs($this->x - $other->x) <= 1 && abs($this->y - $other->y) <= 1;
    }

    public function directionTo(Coordinates $other): Direction
    {
        if (!$this->isAdjacentTo($other)) {
            throw new \InvalidArgumentException("[$other->x,$other->y] is not adjacent to [$this->x,$this->y]].");
        }

        return match (true) {
            $this->x - $other->x === -1 => Direction::RIGHT,
            $this->x - $other->x === 1 => Direction::LEFT,
            $this->y - $other->y === -1 => Direction::UP,
            $this->y - $other->y === 1 => Direction::DOWN,
        };
    }

    /**
     * @param array<Coordinates> $coordinates
     *
     * @return Coordinates
     */
    public function getClosestCoordinates(array $coordinates): Coordinates
    {
        $distances = [];

        foreach ($coordinates as $coordinate) {
            $distances[(string)$coordinate] = $this->getDistanceTo($coordinate);
        }

        asort($distances);

        $coordinates = array_key_first($distances);

        return Coordinates::fromString($coordinates);
    }

    public function getDistanceTo(Coordinates $to): float
    {
        $rowFactor = ($this->x - $to->x) ** 2;
        $columnFactor = ($this->y - $to->y) ** 2;

        return sqrt($rowFactor + $columnFactor);
    }

    public static function dataCastUsing(...$arguments): Cast
    {
        $type = $arguments['spaceType'] ?? null;

        return new class($type) implements Cast {
            public function __construct(
                public ?SpaceType $type = null
            ) {
            }

            public function cast(
                DataProperty $property,
                mixed $value,
                array $properties,
                CreationContext $context
            ): array|Coordinates {
                if (is_array($value) && isset($value[0])) {
                    $values = [];
                    foreach ($value as $item) {
                        $values[] = new Coordinates(
                            x: $item['x'],
                            y: $item['y'],
                            spaceType: $this->type,
                            snakeId: $properties['id'] ?? null,
                        );
                    }
                    return $values;
                } elseif (!empty($value)) {
                    return new Coordinates(
                        x: $value['x'],
                        y: $value['y'],
                        spaceType: $this->type,
                        snakeId: $properties['id'] ?? null,
                    );
                }

                return [];
            }
        };
    }

    public function getUniqueNodeId(): string
    {
        return $this->__toString();
    }
}
