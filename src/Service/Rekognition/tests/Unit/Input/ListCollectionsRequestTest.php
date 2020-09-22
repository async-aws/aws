<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;

class ListCollectionsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListCollectionsRequest([
            'NextToken' => 'NEXT',
            'MaxResults' => 1337,
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_ListCollections.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.ListCollections

                {
                   "MaxResults": 1337,
                   "NextToken": "NEXT"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
