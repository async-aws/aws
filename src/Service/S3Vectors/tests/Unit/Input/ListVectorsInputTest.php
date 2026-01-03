<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListVectorsInput;

class ListVectorsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListVectorsInput([
            'vectorBucketName' => 'my-bucket',
            'indexName' => 'my-index',
            'indexArn' => 'arn:aws:s3:us-east-1:123456789012:index/my-index',
            'maxResults' => 100,
            'nextToken' => 'token123',
            'segmentCount' => 1,
            'segmentIndex' => 0,
            'returnData' => true,
            'returnMetadata' => true,
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListVectors.html
        $expected = '
            POST /ListVectors HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"vectorBucketName":"my-bucket","indexName":"my-index","indexArn":"arn:aws:s3:us-east-1:123456789012:index/my-index","maxResults":100,"nextToken":"token123","segmentCount":1,"segmentIndex":0,"returnData":true,"returnMetadata":true}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
