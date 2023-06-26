<?php

namespace AsyncAws\LocationService\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Enum\DimensionUnit;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\Enum\TravelMode;
use AsyncAws\LocationService\Enum\VehicleWeightUnit;
use AsyncAws\LocationService\Input\CalculateRouteMatrixRequest;
use AsyncAws\LocationService\Input\CalculateRouteRequest;
use AsyncAws\LocationService\Input\SearchPlaceIndexForPositionRequest;
use AsyncAws\LocationService\Input\SearchPlaceIndexForTextRequest;
use AsyncAws\LocationService\LocationServiceClient;
use AsyncAws\LocationService\ValueObject\CalculateRouteTruckModeOptions;
use AsyncAws\LocationService\ValueObject\TruckDimensions;
use AsyncAws\LocationService\ValueObject\TruckWeight;

class LocationServiceClientTest extends TestCase
{
    public function testCalculateRoute(): void
    {
        $client = $this->getClient();

        $input = new CalculateRouteRequest([
            'CalculatorName' => 'CalculatorName',
            'DepartNow' => false,
            'DeparturePositions' => [[-123.115, 49.285]],
            'DepartureTime' => new \DateTimeImmutable('2023-06-27T06:17:31+00:00'),
            'DestinationPositions' => [[-123.115, 49.285]],
            'DistanceUnit' => DistanceUnit::KILOMETERS,
            'TravelMode' => TravelMode::TRUCK,
            'TruckModeOptions' => new CalculateRouteTruckModeOptions([
                'AvoidFerries' => false,
                'AvoidTolls' => false,
                'Dimensions' => new TruckDimensions([
                    'Height' => 4,
                    'Length' => 20,
                    'Unit' => DimensionUnit::METERS,
                    'Width' => 3,
                ]),
                'Weight' => new TruckWeight([
                    'Total' => 20000,
                    'Unit' => VehicleWeightUnit::KILOGRAMS,
                ]),
            ]),
        ]);
        $result = $client->calculateRoute($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getLegs());
        // self::assertTODO(expected, $result->getSummary());
    }

    public function testCalculateRouteMatrix(): void
    {
        $client = $this->getClient();

        $input = new CalculateRouteMatrixRequest([
            'CalculatorName' => 'CalculatorName',
            'DepartNow' => false,
            'DeparturePosition' => [-123.115, 49.285],
            'DepartureTime' => new \DateTimeImmutable('2023-06-27T06:17:31+00:00'),
            'DestinationPosition' => [-123.115, 49.285],
            'DistanceUnit' => DistanceUnit::KILOMETERS,
            'IncludeLegGeometry' => false,
            'TravelMode' => TravelMode::TRUCK,
            'TruckModeOptions' => new CalculateRouteTruckModeOptions([
                'AvoidFerries' => false,
                'AvoidTolls' => false,
                'Dimensions' => new TruckDimensions([
                    'Height' => 4,
                    'Length' => 20,
                    'Unit' => DimensionUnit::METERS,
                    'Width' => 3,
                ]),
                'Weight' => new TruckWeight([
                    'Total' => 20000,
                    'Unit' => VehicleWeightUnit::KILOGRAMS,
                ]),
            ]),
            'WaypointPositions' => [[-123.115, 49.285]],
        ]);
        $result = $client->calculateRouteMatrix($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getRouteMatrix());
        // self::assertTODO(expected, $result->getSnappedDeparturePositions());
        // self::assertTODO(expected, $result->getSnappedDestinationPositions());
        // self::assertTODO(expected, $result->getSummary());
    }

    public function testSearchPlaceIndexForPosition(): void
    {
        $client = $this->getClient();

        $input = new SearchPlaceIndexForPositionRequest([
            'IndexName' => 'IndexName',
            'Language' => 'en',
            'MaxResults' => 1,
            'Position' => [-123.115, 49.285],
        ]);
        $result = $client->searchPlaceIndexForPosition($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getResults());
        // self::assertTODO(expected, $result->getSummary());
    }

    public function testSearchPlaceIndexForText(): void
    {
        $client = $this->getClient();

        $input = new SearchPlaceIndexForTextRequest([
            'BiasPosition' => [-12.7935, -37.4835, -12.0684, -36.9542],
            'FilterCategories' => ['Category'],
            'FilterCountries' => ['CAN'],
            'IndexName' => 'IndexName',
            'Language' => 'en',
            'MaxResults' => 1,
            'Text' => 'Main Street, Toronto, CAN',
        ]);
        $result = $client->searchPlaceIndexForText($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getResults());
        // self::assertTODO(expected, $result->getSummary());
    }

    private function getClient(): LocationServiceClient
    {
        self::markTestSkipped('There is no docker image available for LocationService.');

        return new LocationServiceClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
