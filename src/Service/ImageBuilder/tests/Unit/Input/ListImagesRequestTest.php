<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Enum\Ownership;
use AsyncAws\ImageBuilder\Input\ListImagesRequest;
use AsyncAws\ImageBuilder\ValueObject\Filter;

class ListImagesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListImagesRequest([
            'owner' => Ownership::SELF,
            'filters' => [new Filter([
                'name' => 'platform',
                'values' => ['Linux'],
            ])],
            'byName' => false,
            'maxResults' => 25,
            'nextToken' => 'page-token',
            'includeDeprecated' => false,
        ]);

        // ListImages is POST /ListImages with a JSON body.
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_ListImages.html
        $expected = '
            POST /ListImages HTTP/1.0
            Accept: application/json
            Content-Type: application/json

            {
                "owner": "Self",
                "filters": [
                    {
                        "name": "platform",
                        "values": ["Linux"]
                    }
                ],
                "byName": false,
                "maxResults": 25,
                "nextToken": "page-token",
                "includeDeprecated": false
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
