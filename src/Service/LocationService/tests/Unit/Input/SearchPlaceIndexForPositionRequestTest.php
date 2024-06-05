<?php

namespace AsyncAws\LocationService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Input\SearchPlaceIndexForPositionRequest;

class SearchPlaceIndexForPositionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SearchPlaceIndexForPositionRequest([
            'IndexName' => 'IndexName',
            'Language' => 'en',
            'MaxResults' => 1,
            'Position' => [-123.115, 49.285],
        ]);

        // see https://docs.aws.amazon.com/location/latest/APIReference/API_SearchPlaceIndexForPosition.html
        $expected = '
            POST /places/v0/indexes/IndexName/search/position HTTP/1.0
            Content-type: application/json
            Accept: application/json

            {
                "Language": "en",
                "MaxResults": 1,
                "Position": [-123.115, 49.285]
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
