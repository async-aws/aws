<?php

namespace AsyncAws\ImageBuilder\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\ImageBuilder\Input\ListImageBuildVersionsRequest;
use AsyncAws\ImageBuilder\ValueObject\Filter;

class ListImageBuildVersionsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListImageBuildVersionsRequest([
            'imageVersionArn' => 'arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0',
            'filters' => [new Filter([
                'name' => 'name',
                'values' => ['example'],
            ])],
            'maxResults' => 25,
            'nextToken' => 'page-token',
        ]);

        // ListImageBuildVersions is POST /ListImageBuildVersions with a JSON body.
        // see https://docs.aws.amazon.com/imagebuilder/latest/APIReference/API_ListImageBuildVersions.html
        $expected = '
            POST /ListImageBuildVersions HTTP/1.0
            Accept: application/json
            Content-Type: application/json

            {
                "imageVersionArn": "arn:aws:imagebuilder:us-east-1:123456789012:image/example/1.0.0",
                "filters": [
                    {
                        "name": "name",
                        "values": ["example"]
                    }
                ],
                "maxResults": 25,
                "nextToken": "page-token"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
