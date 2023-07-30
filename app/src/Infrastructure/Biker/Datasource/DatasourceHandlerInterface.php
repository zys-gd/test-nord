<?php

namespace App\Infrastructure\Biker\Datasource;

interface DatasourceHandlerInterface
{
    public function canHandle(string $path): bool;
}
