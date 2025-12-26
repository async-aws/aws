<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\QueryVectorsInput;
use AsyncAws\S3Vectors\ValueObject\VectorDataMemberFloat32;

class QueryVectorsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new QueryVectorsInput([
            'vectorBucketName' => 'my-bucket',
            'indexName' => 'my-index',
            'indexArn' => 'arn:aws:s3:us-east-1:123456789012:index/my-index',
            'topK' => 5,
            'queryVector' => new VectorDataMemberFloat32([
                'float32' => [1.23, 4.56],
            ]),
            'filter' => ['tag' => 'value'],
            'returnMetadata' => true,
            'returnDistance' => true,
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_QueryVectors.html
        $expected = '
            POST /QueryVectors HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"vectorBucketName":"my-bucket","indexName":"my-index","indexArn":"arn:aws:s3:us-east-1:123456789012:index/my-index","topK":5,"queryVector":{"float32":[1.23,4.56]},"returnMetadata":true,"returnDistance":true}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
