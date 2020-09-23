<?php

namespace AsyncAws\Rekognition\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Rekognition\Input\CreateCollectionRequest;

class CreateCollectionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateCollectionRequest([
            'CollectionId' => 'myphotos',
        ]);

        // see https://docs.aws.amazon.com/rekognition/latest/dg/API_CreateCollection.html from SDK

        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-Amz-Target: RekognitionService.CreateCollection

                {
                    "CollectionId": "myphotos"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
