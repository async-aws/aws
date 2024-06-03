<?php

namespace AsyncAws\LocationService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Enum\DimensionUnit;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\Enum\TravelMode;
use AsyncAws\LocationService\Enum\VehicleWeightUnit;
use AsyncAws\LocationService\Input\CalculateRouteMatrixRequest;
use AsyncAws\LocationService\ValueObject\CalculateRouteTruckModeOptions;
use AsyncAws\LocationService\ValueObject\TruckDimensions;
use AsyncAws\LocationService\ValueObject\TruckWeight;

class CalculateRouteMatrixRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CalculateRouteMatrixRequest([
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

        // see https://docs.aws.amazon.com/location/latest/APIReference/API_CalculateRouteMatrix.html
        $expected = '
            POST /routes/v0/calculators/CalculatorName/calculate/route-matrix HTTP/1.0
            Content-type: application/json
            Accept: application/json

            {
              "DepartNow": false,
              "DeparturePositions": [
                [-123.115, 49.285]
              ],
              "DepartureTime": "2023-06-27T06:17:31+00:00",
              "DestinationPositions": [
                [-123.115, 49.285]
              ],
              "DistanceUnit": "Kilometers",
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
              }
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
