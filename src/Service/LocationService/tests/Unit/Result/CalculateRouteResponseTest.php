<?php

namespace AsyncAws\LocationService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Result\CalculateRouteResponse;
use AsyncAws\LocationService\ValueObject\Leg;
use AsyncAws\LocationService\ValueObject\LegGeometry;
use AsyncAws\LocationService\ValueObject\Step;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CalculateRouteResponseTest extends TestCase
{
    public function testCalculateRouteResponse(): void
    {
        // see https://docs.aws.amazon.com/location/latest/APIReference/API_CalculateRoute.html
        $response = new SimpleMockedResponse('{
           "Legs": [
              {
                 "Distance": 10,
                 "DurationSeconds": 1000,
                 "EndPosition": [-123.115, 49.285],
                 "Geometry": {
                    "LineString": [[-123.117, 49.284],[-123.115, 49.285]]
                 },
                 "StartPosition": [-123.117, 49.284],
                 "Steps": [
                    {
                       "Distance": 10,
                       "DurationSeconds": 1000,
                       "EndPosition": [-123.115, 49.285],
                       "GeometryOffset": 0,
                       "StartPosition": [-123.117, 49.284]
                    }
                 ]
              }
           ],
           "Summary": {
              "DataSource": "Esri",
              "Distance": 10,
              "DistanceUnit": "Kilometers",
              "DurationSeconds": 1000,
              "RouteBBox": [-123.117, 49.284, -123.115, 49.285]
           }
        }');

        $client = new MockHttpClient($response);
        $result = new CalculateRouteResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $legs = $result->getLegs();

        self::assertCount(1, $legs);
        self::assertInstanceOf(Leg::class, $legs[0]);
        self::assertSame(10.0, $legs[0]->getDistance());
        self::assertSame(1000.0, $legs[0]->getDurationSeconds());
        self::assertSame([-123.117, 49.284], $legs[0]->getStartPosition());
        self::assertSame([-123.115, 49.285], $legs[0]->getEndPosition());

        $legGeometry = $legs[0]->getGeometry();
        self::assertInstanceOf(LegGeometry::class, $legGeometry);
        self::assertSame([[-123.117, 49.284], [-123.115, 49.285]], $legGeometry->getLineString());

        $steps = $legs[0]->getSteps();
        self::assertCount(1, $steps);
        self::assertInstanceOf(Step::class, $steps[0]);
        self::assertSame(10.0, $steps[0]->getDistance());
        self::assertSame(1000.0, $steps[0]->getDurationSeconds());
        self::assertSame([-123.117, 49.284], $steps[0]->getStartPosition());
        self::assertSame([-123.115, 49.285], $steps[0]->getEndPosition());

        $summary = $result->getSummary();
        self::assertSame(10.0, $summary->getDistance());
        self::assertSame(1000.0, $summary->getDurationSeconds());
        self::assertSame('Kilometers', $summary->getDistanceUnit());
        self::assertSame('Esri', $summary->getDataSource());
        self::assertSame([-123.117, 49.284, -123.115, 49.285], $summary->getRoutebbox());
    }
}
