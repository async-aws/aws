<?php

namespace AsyncAws\LocationService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Result\CalculateRouteMatrixResponse;
use AsyncAws\LocationService\ValueObject\CalculateRouteMatrixSummary;
use AsyncAws\LocationService\ValueObject\RouteMatrixEntry;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CalculateRouteMatrixResponseTest extends TestCase
{
    public function testCalculateRouteMatrixResponse(): void
    {
        // see https://docs.aws.amazon.com/location/latest/APIReference/API_CalculateRouteMatrix.html
        $response = new SimpleMockedResponse('{
           "RouteMatrix": [
              [
                 {
                    "Distance": 10000,
                    "DurationSeconds": 10000
                 }
              ]
           ],
           "SnappedDeparturePositions": [
              [-123.115, 49.285]
           ],
           "SnappedDestinationPositions": [
              [-122.339, 47.615]
           ],
           "Summary": {
              "DataSource": "Esri",
              "DistanceUnit": "Kilometers",
              "ErrorCount": 0,
              "RouteCount": 1
           }
        }');

        $client = new MockHttpClient($response);
        $result = new CalculateRouteMatrixResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals(
            [
                [
                    new RouteMatrixEntry([
                        'Distance' => 10000.0,
                        'DurationSeconds' => 10000.0,
                    ]),
                ],
            ],
            $result->getRouteMatrix()
        );
        self::assertSame([[-123.115, 49.285]], $result->getSnappedDeparturePositions());
        self::assertSame([[-122.339, 47.615]], $result->getSnappedDestinationPositions());
        self::assertEquals(
            new CalculateRouteMatrixSummary([
                'DataSource' => 'Esri',
                'DistanceUnit' => 'Kilometers',
                'ErrorCount' => 0,
                'RouteCount' => 1,
            ]),
            $result->getSummary()
        );
    }
}
