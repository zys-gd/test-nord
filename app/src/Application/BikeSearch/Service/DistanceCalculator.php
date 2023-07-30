<?php

declare(strict_types=1);

namespace App\Application\BikeSearch\Service;

use App\Application\BikeSearch\Exception\InvalidCoordinatesException;

class DistanceCalculator
{
    private const EARTH_RADIUS = 6371;
    private const MIN_LONGITUDE = -180;
    private const MAX_LONGITUDE = 180;
    private const MIN_LATITUDE = -90;
    private const MAX_LATITUDE = 90;

    /**
     * @throws InvalidCoordinatesException
     */
    public function getDistance(float $latitude1, float $longitude1, float $latitude2, float $longitude2): float
    {
        if (
            !$this->validateCoords($latitude1, self::MIN_LATITUDE, self::MAX_LATITUDE)
            || !$this->validateCoords($latitude2, self::MIN_LATITUDE, self::MAX_LATITUDE)
            || !$this->validateCoords($longitude1, self::MIN_LONGITUDE, self::MAX_LONGITUDE)
            || !$this->validateCoords($longitude2, self::MIN_LONGITUDE, self::MAX_LONGITUDE)
        ) {
            throw new InvalidCoordinatesException();
        }
        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));

        return self::EARTH_RADIUS * $c;
    }

    private function validateCoords(float $coord, float $min, float $max): bool
    {
        return !!filter_var(
            $coord,
            FILTER_VALIDATE_FLOAT,
            [
                'options' => [
                    'min_range' => $min,
                    'max_range' => $max,
                ],
            ]
        );
    }
}
