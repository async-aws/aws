<?php

namespace AsyncAws\LocationService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Enum\DimensionUnit;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\Enum\TravelMode;
use AsyncAws\LocationService\Enum\VehicleWeightUnit;
use AsyncAws\LocationService\Input\CalculateRouteRequest;
use AsyncAws\LocationService\ValueObject\CalculateRouteTruckModeOptions;
use AsyncAws\LocationService\ValueObject\TruckDimensions;
use AsyncAws\LocationService\ValueObject\TruckWeight;

class CalculateRouteRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CalculateRouteRequest([
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

        // see https://docs.aws.amazon.com/location/latest/APIReference/API_CalculateRoute.html
        $expected = '
            POST /routes/v0/calculators/CalculatorName/calculate/route HTTP/1.0
            Content-Type: application/json

            {
                "DepartNow": false,
                "DeparturePosition": [-123.115, 49.285],
                "DepartureTime": "2023-06-27T06:17:31+00:00",
                "DestinationPosition": [-123.115, 49.285],
                "DistanceUnit": "Kilometers",
                "IncludeLegGeometry": false,
                "TravelMode": "Truck",
                "TruckModeOptions": {
                    "AvoidFerries": false,
                    "AvoidTolls": false,
                    "Dimensions": {
                        "Height": 4,
                        "Length": 20,
                        "Unit": "Meters",
                        "Width": 3
                    },
                    "Weight": {
                        "Total": 20000,
                        "Unit": "Kilograms"
                    }
                },
                "WaypointPositions": [
                    [-123.115, 49.285]
                ]
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
