<?php

declare(strict_types=1);

namespace App\Infrastructure\CityBikes\Http;

use App\Infrastructure\CityBikes\Datasource\BikeStationDatasourceInterface;
use App\Infrastructure\CityBikes\Dto\NetworkEndpointDto;
use App\Infrastructure\CityBikes\Exception\NoNetworkFoundException;
use App\Infrastructure\CityBikes\Exception\ResponseException;
use JMS\Serializer\ArrayTransformerInterface;

class CityBikesClient implements BikeStationDatasourceInterface
{
    private const API_ENDPOINT = 'http://api.citybik.es';
    private const API_NETWORKS_URN = '/v2/networks';

    public function __construct(private ArrayTransformerInterface $serializer)
    {
    }

    /**
     * @throws ResponseException
     * @throws NoNetworkFoundException
     */
    public function fetchNetworkStations(string $city): array
    {
        $networkEndpointDto = $this->fetchNetworkEndpointForCity($city);
        $response = $this->makeRequest($networkEndpointDto->endpoint . '?fields=stations');
        if (isset($response['network']['stations'])) {

            return $this->serializer->fromArray(
                $response['network']['stations'],
                'array<App\Infrastructure\CityBikes\Dto\BikeStationDto>',
            );
        }
        throw new ResponseException();
    }

    /**
     * @throws NoNetworkFoundException
     * @throws ResponseException
     */
    private function fetchNetworkEndpointForCity(string $city): NetworkEndpointDto
    {
        $response = $this->makeRequest(self::API_NETWORKS_URN . '?fields=location,href');
        if (isset($response['networks'])) {
            $networks = $response['networks'];
        } else {
            throw new ResponseException();
        }

        $networksCount = count($networks);
        for ($i = 0; $i < $networksCount; $i++) {
            if ($networks[$i]['location']['city'] == $city) {
                return new NetworkEndpointDto($networks[$i]['href']);
            }
        }
        throw new NoNetworkFoundException();
    }

    private function makeRequest(string $endpoint): array
    {
        return json_decode(file_get_contents(self::API_ENDPOINT . $endpoint), true);
    }
}
