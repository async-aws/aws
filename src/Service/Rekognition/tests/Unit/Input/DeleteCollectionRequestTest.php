<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\DeleteCollectionRequest;

class DeleteCollectionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

                $input = new DeleteCollectionRequest([
                            'CollectionId' => 'change me',
                        ]);

                // see example-1.json from SDK
                $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "CollectionId": "myphotos"
        }
                ';

                self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
