<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\DeleteCollectionRequest;

class DeleteCollectionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteCollectionRequest([
            'CollectionId' => 'test-collection',
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_DeleteCollection.html from SDK
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.DeleteCollection

                {
                    "CollectionId": "test-collection"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
