<?php

namespace AsyncAws\LocationService\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\LocationService\Input\SearchPlaceIndexForTextRequest;

class SearchPlaceIndexForTextRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SearchPlaceIndexForTextRequest([
            'BiasPosition' => [-12.7935, -37.4835, -12.0684, -36.9542],
            'FilterCategories' => ['Category'],
            'FilterCountries' => ['CAN'],
            'IndexName' => 'IndexName',
            'Language' => 'en',
            'MaxResults' => 1,
            'Text' => 'Main Street, Toronto, CAN',
        ]);

        // see https://docs.aws.amazon.com/location/latest/APIReference/API_SearchPlaceIndexForText.html
        $expected = '
            POST /places/v0/indexes/IndexName/search/text HTTP/1.0
            Content-Type: application/json

            {
              "BiasPosition": [
                -12.7935,
                -37.4835,
                -12.0684,
                -36.9542
              ],
              "FilterCategories": [
                "Category"
              ],
              "FilterCountries": [
                "CAN"
              ],
              "Language": "en",
              "MaxResults": 1,
              "Text": "Main Street, Toronto, CAN"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
