<?php

declare(strict_types=1);

namespace App\Application\BikeSearch\Dto;

final readonly class BikeStationDto
{
    public function __construct(
        public float $distance,
        public string $name,
        public int $freeBikeCount,
        public int $bikerCount
    ) {
    }
}
