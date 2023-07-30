<?php

declare(strict_types=1);

namespace App\Infrastructure\Biker\Datasource;

use App\Infrastructure\Biker\Datasource\Exception\ImplementationDoesNotExistException;
use Traversable;

class BikersDataDatasourceProvider
{
    /**
     * @var DatasourceHandlerInterface[]
     */
    private array $handlers;

    /**
     * @param Traversable<DatasourceHandlerInterface> $collectionHandlers
     */
    public function __construct(Traversable $collectionHandlers)
    {
        $this->handlers = iterator_to_array($collectionHandlers);
    }

    public function getHandler(string $path): BikersDataDatasourceInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->canHandle($path)) {
                return $handler;
            }
        }

        throw new ImplementationDoesNotExistException();
    }
}
