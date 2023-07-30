<?php

declare(strict_types=1);

namespace App\Infrastructure\Biker\Datasource\Service;

class FileParser
{
    public function parseFromSourceAsString(string $path): string
    {
        return file_get_contents($path);
    }
}
