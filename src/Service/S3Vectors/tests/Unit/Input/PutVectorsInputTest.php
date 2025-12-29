<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\PutVectorsInput;
use AsyncAws\S3Vectors\ValueObject\PutInputVector;
use AsyncAws\S3Vectors\ValueObject\VectorData;

class PutVectorsInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutVectorsInput([
            'vectorBucketName' => 'change me',
            'indexName' => 'change me',
            'indexArn' => 'change me',
            'vectors' => [new PutInputVector([
                'key' => 'change me',
                'data' => new VectorData([
                    'float32' => [1337],
                ]),
                'metadata' => 'change me',
            ])],
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_PutVectors.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
