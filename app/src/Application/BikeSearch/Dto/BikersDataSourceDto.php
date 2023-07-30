<?php

declare(strict_types=1);

namespace App\Application\BikeSearch\Dto;

final readonly class BikersDataSourceDto
{
    public function __construct(public string $path)
    {
    }
}
