<?php

declare(strict_types=1);

namespace App\Infrastructure\Biker\Dto;

final readonly class BikerDto
{
    public function __construct(
        public int $count,
        public float $latitude,
        public float $longitude,
    ) {
    }
}
