<?php

declare(strict_types=1);

namespace App\Infrastructure\Biker\Datasource\Handler;

use App\Infrastructure\Biker\Datasource\BikersDataDatasourceInterface;
use App\Infrastructure\Biker\Datasource\DatasourceHandlerInterface;
use App\Infrastructure\Biker\Datasource\Service\FileParser;
use JMS\Serializer\ArrayTransformerInterface;

class CsvHandler implements DatasourceHandlerInterface, BikersDataDatasourceInterface
{
    public function __construct(
        private readonly ArrayTransformerInterface $serializer,
        private readonly FileParser $fileParser
    ) {
    }

    public function canHandle(string $path): bool
    {
        return !!preg_match('#\.csv$#', strtolower($path));
    }

    public function fetchBikersData(string $path): array
    {
        $bikers_data = explode("\n", $this->fileParser->parseFromSourceAsString($path));

        $bikers = [];
        for ($i = 0; $i < count($bikers_data); $i++) {
            if ($i == 0) {
                continue;
            } else {
                $biker_info = explode(',', $bikers_data[$i]);
                $bikers[] = [
                    'count' => $biker_info[0],
                    'latitude' => $biker_info[1],
                    'longitude' => $biker_info[2],
                ];
            }
        }

        return $this->serializer->fromArray($bikers, 'array<App\Infrastructure\Biker\Dto\BikerDto>');
    }
}
