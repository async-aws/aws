<?php

namespace AsyncAws\LocationService\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Input\CalculateRouteMatrixRequest;
use AsyncAws\LocationService\Input\CalculateRouteRequest;
use AsyncAws\LocationService\Input\SearchPlaceIndexForPositionRequest;
use AsyncAws\LocationService\Input\SearchPlaceIndexForTextRequest;
use AsyncAws\LocationService\LocationServiceClient;
use AsyncAws\LocationService\Result\CalculateRouteMatrixResponse;
use AsyncAws\LocationService\Result\CalculateRouteResponse;
use AsyncAws\LocationService\Result\SearchPlaceIndexForPositionResponse;
use AsyncAws\LocationService\Result\SearchPlaceIndexForTextResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class LocationServiceClientTest extends TestCase
{
    public function testCalculateRoute(): void
    {
        $client = new LocationServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new CalculateRouteRequest([
            'CalculatorName' => 'CalculatorName',
            'DeparturePosition' => [-123.115, 49.285],
            'DestinationPosition' => [-123.115, 49.285],
        ]);
        $result = $client->calculateRoute($input);

        self::assertInstanceOf(CalculateRouteResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
        self::assertEquals('https://routes.geo.us-east-1.amazonaws.com/routes/v0/calculators/CalculatorName/calculate/route', $result->info()['response']->getInfo('url'));
    }

    public function testCalculateRouteMatrix(): void
    {
        $client = new LocationServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new CalculateRouteMatrixRequest([
            'CalculatorName' => 'CalculatorName',
            'DeparturePositions' => [[-123.115, 49.285]],
            'DestinationPositions' => [[-123.115, 49.285]],
        ]);
        $result = $client->calculateRouteMatrix($input);

        self::assertInstanceOf(CalculateRouteMatrixResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
        self::assertEquals('https://routes.geo.us-east-1.amazonaws.com/routes/v0/calculators/CalculatorName/calculate/route-matrix', $result->info()['response']->getInfo('url'));
    }

    public function testSearchPlaceIndexForPosition(): void
    {
        $client = new LocationServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new SearchPlaceIndexForPositionRequest([
            'IndexName' => 'IndexName',
            'Position' => [-123.115, 49.285],
        ]);
        $result = $client->searchPlaceIndexForPosition($input);

        self::assertInstanceOf(SearchPlaceIndexForPositionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
        self::assertEquals('https://places.geo.us-east-1.amazonaws.com/places/v0/indexes/IndexName/search/position', $result->info()['response']->getInfo('url'));
    }

    public function testSearchPlaceIndexForText(): void
    {
        $client = new LocationServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new SearchPlaceIndexForTextRequest([
            'IndexName' => 'IndexName',
            'Text' => '123 Any Street',
        ]);
        $result = $client->searchPlaceIndexForText($input);

        self::assertInstanceOf(SearchPlaceIndexForTextResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
        self::assertEquals('https://places.geo.us-east-1.amazonaws.com/places/v0/indexes/IndexName/search/text', $result->info()['response']->getInfo('url'));
    }
}
