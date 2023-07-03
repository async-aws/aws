<?php

namespace AsyncAws\LocationService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Result\SearchPlaceIndexForPositionResponse;
use AsyncAws\LocationService\ValueObject\Place;
use AsyncAws\LocationService\ValueObject\PlaceGeometry;
use AsyncAws\LocationService\ValueObject\SearchForPositionResult;
use AsyncAws\LocationService\ValueObject\SearchPlaceIndexForPositionSummary;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SearchPlaceIndexForPositionResponseTest extends TestCase
{
    public function testSearchPlaceIndexForPositionResponse(): void
    {
        // see https://docs.aws.amazon.com/location/latest/APIReference/API_SearchPlaceIndexForPosition.html
        $response = new SimpleMockedResponse(
            '{
           "Results": [
              {
                 "Distance": 1000,
                 "Place": {
                    "Country": "CAN",
                    "Geometry": {
                       "Point": [ 43.682703, -79.299419 ]
                    },
                    "Interpolated": false,
                    "Label": "123 Main Street, Toronto, CAN",
                    "Municipality": "Toronto",
                    "Neighborhood": "Downtown",
                    "Region": "British Columbia",
                    "Street": "Main Street",
                    "SubRegion": "Metro Vancouver"
                 },
                 "PlaceId": "PlaceId"
              }
           ],
           "Summary": {
              "DataSource": "Esri",
              "Language": "en",
              "MaxResults": 1,
              "Position": [ 43.682703, -79.299419 ]
           }
        }'
        );

        $client = new MockHttpClient($response);
        $result = new SearchPlaceIndexForPositionResponse(
            new Response($client->request('POST', 'http://localhost'), $client, new NullLogger())
        );

        self::assertEquals(
            [
                new SearchForPositionResult([
                    'Distance' => 1000.0,
                    'Place' => new Place([
                        'Country' => 'CAN',
                        'Geometry' => new PlaceGeometry(['Point' => [43.682703, -79.299419]]),
                        'Interpolated' => false,
                        'Label' => '123 Main Street, Toronto, CAN',
                        'Municipality' => 'Toronto',
                        'Neighborhood' => 'Downtown',
                        'Region' => 'British Columbia',
                        'Street' => 'Main Street',
                        'SubRegion' => 'Metro Vancouver',
                    ]),
                    'PlaceId' => 'PlaceId',
                ]),
            ],
            $result->getResults()
        );
        self::assertEquals(
            new SearchPlaceIndexForPositionSummary([
                'DataSource' => 'Esri',
                'Language' => 'en',
                'MaxResults' => 1,
                'Position' => [43.682703, -79.299419],
            ]),
            $result->getSummary()
        );
    }
}
