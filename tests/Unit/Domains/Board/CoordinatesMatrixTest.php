<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Board;

use App\Domains\Board\Coordinates;
use App\Domains\Board\CoordinatesMatrix;
use PHPUnit\Framework\TestCase;

class CoordinatesMatrixTest extends TestCase
{
    public const int BOARD_WIDTH = 5;
    public const int BOARD_HEIGHT = 5;

    /**
     * @var array<Coordinates>
     */
    public array $occupiedCoordinates;

    /**
     * @var array<Coordinates>
     */
    public array $unoccupiedCoordinates;

    public function setUp(): void
    {
        for ($i = 0; $i < self::BOARD_WIDTH; $i++) {
            for ($j = 0; $j < self::BOARD_HEIGHT; $j++) {
                if (rand(0, 1)) {
                    $this->occupiedCoordinates[] = new Coordinates($i, $j);
                } else {
                    $this->unoccupiedCoordinates[] = new Coordinates($i, $j);
                }
            }
        }
    }

    public function testSpaceIsEmpty()
    {
        $coordinatesMatrix = new CoordinatesMatrix($this->occupiedCoordinates);
        foreach ($this->unoccupiedCoordinates as $coordinate) {
            $this->assertTrue($coordinatesMatrix->spaceIsEmpty($coordinate));
        }
    }

    public function testSpaceIsOccupied()
    {
        $coordinatesMatrix = new CoordinatesMatrix($this->occupiedCoordinates);
        foreach ($this->occupiedCoordinates as $coordinate) {
            $this->assertTrue($coordinatesMatrix->spaceIsOccupied($coordinate));
        }
    }

    public function testConstructor()
    {
        $coordinatesMatrix = new CoordinatesMatrix($this->occupiedCoordinates);

        $this->assertIsArray($coordinatesMatrix->matrix);
        foreach ($this->occupiedCoordinates as $coordinate) {
            $this->assertTrue(isset($coordinatesMatrix->matrix[$coordinate->x][$coordinate->y]));
        }
    }
}
