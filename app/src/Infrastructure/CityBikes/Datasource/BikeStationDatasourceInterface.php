<?php

namespace App\Infrastructure\CityBikes\Datasource;

use App\Infrastructure\CityBikes\Dto\BikeStationDto;

interface BikeStationDatasourceInterface
{
    /**
     * @return BikeStationDto[]
     */
    public function fetchNetworkStations(string $city): array;
}
