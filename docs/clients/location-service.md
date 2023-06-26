---
layout: client
category: clients
name: LocationService
package: async-aws/location-service
---

## Usage

### CalculateRoute

```php
use AsyncAws\LocationService\LocationServiceClient;
use AsyncAws\LocationService\Enum\DimensionUnit;
use AsyncAws\LocationService\Enum\DistanceUnit;
use AsyncAws\LocationService\Enum\TravelMode;
use AsyncAws\LocationService\Enum\VehicleWeightUnit;
use AsyncAws\LocationService\Input\CalculateRouteRequest;
use AsyncAws\LocationService\ValueObject\CalculateRouteTruckModeOptions;
use AsyncAws\LocationService\ValueObject\TruckDimensions;
use AsyncAws\LocationService\ValueObject\TruckWeight;

$location = new LocationServiceClient();

$request = $location->calculateRoute(new CalculateRouteRequest([
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
]));

$request->getSummary();
