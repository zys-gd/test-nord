<?php

declare(strict_types=1);

namespace App\Application\BikeSearch\Query;

use App\Application\BikeSearch\Dto\BikersDataSourceDto;
use App\Application\BikeSearch\Dto\BikeSearchDto;
use App\Application\BikeSearch\Dto\BikeStationDto;
use App\Application\BikeSearch\Exception\InvalidCoordinatesException;
use App\Application\BikeSearch\Service\DistanceCalculator;
use App\Infrastructure\Biker\Datasource\BikersDataDatasourceProvider;
use App\Infrastructure\CityBikes\Exception\NoNetworkFoundException;
use App\Infrastructure\CityBikes\Exception\ResponseException;
use App\Infrastructure\CityBikes\Http\CityBikesClient;

class BikeSearchUseCase
{
    public function __construct(
        private readonly CityBikesClient $cityBikesClient,
        private BikersDataDatasourceProvider $provider,
        private DistanceCalculator $distanceCalculator
    ) {
    }

    /**
     * @return BikeStationDto[]
     *
     * @throws ResponseException
     * @throws NoNetworkFoundException
     * @throws InvalidCoordinatesException
     */
    public function search(BikeSearchDto $bikeSearchDto, BikersDataSourceDto $bikersDataSourceDto): array
    {
        $bikersData = $this->provider
            ->getHandler($bikersDataSourceDto->path)
            ->fetchBikersData($bikersDataSourceDto->path);
        $stations = $this->cityBikesClient->fetchNetworkStations($bikeSearchDto->city);

        $result = [];

        foreach ($bikersData as $biker) {
            $shortestDistance = 9999999999999999;
            $closestStationName = '';
            $freeBikeCount = 0;
            $bikerCount = 0;
            foreach ($stations as $station) {
                $distance = $this->distanceCalculator->getDistance(
                    $station->latitude,
                    $station->longitude,
                    $biker->latitude,
                    $biker->longitude
                );
                if ($distance < $shortestDistance) {
                    $shortestDistance = $distance;
                    $closestStationName = $station->name;
                    $freeBikeCount = $station->freeBikes;
                    $bikerCount = $biker->count;
                }
            }
            $result[] = new BikeStationDto(
                $shortestDistance,
                $closestStationName,
                $freeBikeCount,
                $bikerCount,
            );
        }

        return $result;
    }
}
