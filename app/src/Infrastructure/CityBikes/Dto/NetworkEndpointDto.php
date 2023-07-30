<?php

declare(strict_types=1);

namespace App\Infrastructure\CityBikes\Dto;

final readonly class NetworkEndpointDto
{
    public function __construct(public string $endpoint)
    {
    }
}
