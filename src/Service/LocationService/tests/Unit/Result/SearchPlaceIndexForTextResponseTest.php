<?php

namespace AsyncAws\LocationService\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Result\SearchPlaceIndexForTextResponse;
use AsyncAws\LocationService\ValueObject\Place;
use AsyncAws\LocationService\ValueObject\PlaceGeometry;
use AsyncAws\LocationService\ValueObject\SearchForTextResult;
use AsyncAws\LocationService\ValueObject\SearchPlaceIndexForTextSummary;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SearchPlaceIndexForTextResponseTest extends TestCase
{
    public function testSearchPlaceIndexForTextResponse(): void
    {
        // see https://docs.aws.amazon.com/location/latest/APIReference/API_SearchPlaceIndexForText.html
        $response = new SimpleMockedResponse('{
           "Results": [
              {
                 "Distance": 10,
                 "Place": {
                    "Country": "CAN",
                    "Geometry": {
                       "Point": [ 43.682703, -79.299419 ]
                    },
                    "Interpolated": false,
                    "Label": "Main Street, Toronto, CAN",
                    "Municipality": "Toronto",
                    "Neighborhood": "Downtown",
                    "Region": "British Columbia",
                    "Street": "Main Street",
                    "SubRegion": "Metro Vancouver"
                 },
                 "PlaceId": "PlaceId",
                 "Relevance": 0.01
              }
           ],
           "Summary": {
              "DataSource": "Esri",
              "Language": "en",
              "MaxResults": 1,
              "Text": "Main Street, Toronto, CAN"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new SearchPlaceIndexForTextResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals(
            [
                new SearchForTextResult([
                    'Distance' => 10.0,
                    'Place' => new Place([
                        'Country' => 'CAN',
                        'Geometry' => new PlaceGeometry(['Point' => [43.682703, -79.299419]]),
                        'Interpolated' => false,
                        'Label' => 'Main Street, Toronto, CAN',
                        'Municipality' => 'Toronto',
                        'Neighborhood' => 'Downtown',
                        'Region' => 'British Columbia',
                        'Street' => 'Main Street',
                        'SubRegion' => 'Metro Vancouver',
                    ]),
                    'PlaceId' => 'PlaceId',
                    'Relevance' => 0.01,
                ]),
            ],
            $result->getResults()
        );
        self::assertEquals(
            new SearchPlaceIndexForTextSummary([
                'DataSource' => 'Esri',
                'Language' => 'en',
                'MaxResults' => 1,
                'Text' => 'Main Street, Toronto, CAN',
            ]),
            $result->getSummary()
        );
    }
}
