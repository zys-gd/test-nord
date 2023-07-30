<?php

declare(strict_types=1);

namespace App\Tests\Unit\App\Infrastructure\Biker\Datasource\Handler;

use App\Infrastructure\Biker\Datasource\Handler\CsvHandler;
use App\Infrastructure\Biker\Datasource\Service\FileParser;
use App\Infrastructure\Biker\Dto\BikerDto;
use Generator;
use JMS\Serializer\ArrayTransformerInterface;
use PHPUnit\Framework\TestCase;

class CsvHandlerTest extends TestCase
{
    private FileParser $fileParser;
    private CsvHandler $csvHandler;
    private ArrayTransformerInterface $serializer;

    protected function setUp(): void
    {
        $this->fileParser = $this->createMock(FileParser::class);
        $this->serializer = $this->createMock(ArrayTransformerInterface::class);
        $this->csvHandler = new CsvHandler($this->serializer, $this->fileParser);
    }

    /**
     * @dataProvider canHandleData
     */
    public function testHandleCheck(string $path, bool $expectation): void
    {
        $this->assertEquals($expectation, $this->csvHandler->canHandle($path));
    }

    /**
     * @dataProvider fetchBikersDataData
     */
    public function testParseSuccess(array $bikerDtos): void
    {
        $this->serializer
            ->expects($this->once())
            ->method('fromArray')
            ->willReturn($bikerDtos);
        $this->assertEquals($bikerDtos, $this->csvHandler->fetchBikersData(''));
    }

    private function canHandleData(): Generator
    {
        yield 'csv' => ['some.name.csv', true];
        yield 'CSV' => ['some.name.CSV', true];
        yield 'txt' => ['some.name.txt', false];
        yield 'csvv' => ['some.name.csvv', false];
        yield 'name_csv' => ['some.name_csv', false];
        yield 'namecsv' => ['some.namecsv', false];
    }

    public function fetchBikersDataData(): Generator
    {
        yield '[]' => [[]];
        yield '[BikerDto]' => [[new BikerDto(1, 1.1, 1.1)]];
    }
}
