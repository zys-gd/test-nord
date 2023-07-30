<?php

declare(strict_types=1);

namespace App\Infrastructure\CityBikes\Dto;

final readonly class BikeStationDto
{
    public function __construct(
        public int $emptySlots,
        public int $freeBikes,
        public string $id,
        public float $latitude,
        public float $longitude,
        public string $name,
        public string $timestamp
    ) {
    }
}
