<?php

declare(strict_types=1);

namespace App\Application\BikeSearch\Dto;

final readonly class BikeSearchDto
{
    public function __construct(
        public string $city
    ) {
    }
}
