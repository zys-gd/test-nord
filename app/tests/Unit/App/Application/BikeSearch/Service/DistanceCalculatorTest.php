<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\Application\BikeSearch\Service;

use App\Application\BikeSearch\Exception\InvalidCoordinatesException;
use App\Application\BikeSearch\Service\DistanceCalculator;
use Generator;
use PHPUnit\Framework\TestCase;

class DistanceCalculatorTest extends TestCase
{
    private DistanceCalculator $distanceCalculator;

    protected function setUp(): void
    {
        $this->distanceCalculator = new DistanceCalculator();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetDistance(
        float $latitude1,
        float $longitude1,
        float $latitude2,
        float $longitude2,
        float $result
    ): void {
        $this->assertEquals(
            $result,
            $this->distanceCalculator->getDistance($latitude1, $longitude1, $latitude2, $longitude2)
        );
    }

    /**
     * @dataProvider dataProviderForException
     */
    public function testGetDistanceException(
        float $latitude1,
        float $longitude1,
        float $latitude2,
        float $longitude2
    ): void {
        $this->expectException(InvalidCoordinatesException::class);
        $this->distanceCalculator->getDistance($latitude1, $longitude1, $latitude2, $longitude2);
    }

    public function dataProvider(): Generator
    {
        yield [1, 1, 1, 1, 0];
        yield [45.69233, 9.65931, 45.699617, 9.677247, 1.6116000556406356];
        yield [-90, -180, 90, 180, 20015.086796020572];
        yield [90, 180, -90, -180, 20015.086796020572];
    }

    public function dataProviderForException(): Generator
    {
        yield [-91, -180, 90, 180];
        yield [91, 180, -90, -180];
        yield [-90, -181, 90, 180];
        yield [90, 181, -90, -180];
        yield [-90, -180, 91, 180];
        yield [90, 180, -91, -180];
        yield [-90, -180, 90, 181];
        yield [90, 180, -90, -181];
    }
}
