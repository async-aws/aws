<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\PutVectorsInput;
use AsyncAws\S3Vectors\ValueObject\PutInputVector;
use AsyncAws\S3Vectors\ValueObject\VectorDataMemberFloat32;

class PutVectorsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutVectorsInput([
            'vectorBucketName' => 'my-bucket',
            'indexName' => 'my-index',
            'indexArn' => 'arn:aws:s3:us-east-1:123456789012:index/my-index',
            'vectors' => [new PutInputVector([
                'key' => 'key1',
                'data' => new VectorDataMemberFloat32([
                    'float32' => [1.1, 2.2],
                ]),
                'metadata' => ['tag' => 'value'],
            ])],
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_PutVectors.html
        $expected = '
                POST /PutVectors HTTP/1.0
                Content-Type: application/json
                Accept: application/json

                {"vectorBucketName":"my-bucket","indexName":"my-index","indexArn":"arn:aws:s3:us-east-1:123456789012:index/my-index","vectors":[{"data":{"float32":[1.1,2.2]},"key":"key1","metadata":{"tag":"value"}}]}
            ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
