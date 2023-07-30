<?php

declare(strict_types=1);

namespace App\Application\Console;

use App\Application\BikeSearch\Dto\BikersDataSourceDto;
use App\Application\BikeSearch\Dto\BikeSearchDto;
use App\Application\BikeSearch\Exception\InvalidCoordinatesException;
use App\Application\BikeSearch\Query\BikeSearchUseCase;
use App\Infrastructure\Biker\Datasource\Exception\ImplementationDoesNotExistException;
use App\Infrastructure\CityBikes\Exception\NoNetworkFoundException;
use App\Infrastructure\CityBikes\Exception\ResponseException;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'app:bike-share',
    description: 'Show closest available bike to your city'
)]
class BikeSharingConsole extends Command
{
    private const ARGUMENT_CITY_NAME = 'city';
    private const ARGUMENT_BIKERS_SOURCE_NAME = 'bikers';

    public function __construct(
        private readonly BikeSearchUseCase $bikeSearchUseCase,
        private readonly ArrayTransformerInterface $transformer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            self::ARGUMENT_CITY_NAME,
            InputArgument::REQUIRED,
            'Set your city with capital letter and all special chars'
        )
            ->addArgument(
                self::ARGUMENT_BIKERS_SOURCE_NAME,
                InputArgument::OPTIONAL,
                'Path to the source of bikers data. Current implementation support only CSV',
                'https://raw.githubusercontent.com/NordLocker/php-candidate-task/main/bikers.csv'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $city = new BikeSearchDto($input->getArgument(self::ARGUMENT_CITY_NAME));
        $bikersDataSource = new BikersDataSourceDto($input->getArgument(self::ARGUMENT_BIKERS_SOURCE_NAME));
        try {
            $bikeStationDto = $this->bikeSearchUseCase->search($city, $bikersDataSource);

            $io->table(
                [
                    'distance',
                    'name',
                    'free bike count',
                    'biker count',
                ],
                $this->transformer->toArray($bikeStationDto)
            );
        } catch (NoNetworkFoundException) {
            $io->error(sprintf('There is no bike network for selected city: "%s"', $city->city));
        } catch (ResponseException) {
            $io->error('Wrong response from CityBikes');
        } catch (ImplementationDoesNotExistException) {
            $io->error('Cant parse bikers data from selected source');
        } catch (InvalidCoordinatesException) {
            $io->error('Wrong coordinates');
        } catch (Throwable $e) {
            $io->error($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
