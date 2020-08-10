<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;

class ListCollectionsRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

                $input = new ListCollectionsRequest([
                            'NextToken' => 'change me',
        'MaxResults' => 1337,
                        ]);

                // see https://docs.aws.amazon.com/rekognition/latest/APIReference/API_ListCollections.html
                $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            []
                ';

                self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
