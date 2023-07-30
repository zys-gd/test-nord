<?php

namespace App\Infrastructure\Biker\Datasource;

use App\Infrastructure\Biker\Dto\BikerDto;

interface BikersDataDatasourceInterface
{
    /**
     * @param string $path
     *
     * @return BikerDto[]
     */
    public function fetchBikersData(string $path): array;
}
