<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListIndexesInput;

class ListIndexesInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListIndexesInput([
            'vectorBucketName' => 'my-bucket',
            'vectorBucketArn' => 'arn:aws:s3:us-east-1:123456789012:bucket/my-bucket',
            'maxResults' => 25,
            'nextToken' => 'token123',
            'prefix' => 'index-',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListIndexes.html
        $expected = '
            POST /ListIndexes HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"vectorBucketName":"my-bucket","vectorBucketArn":"arn:aws:s3:us-east-1:123456789012:bucket/my-bucket","maxResults":25,"nextToken":"token123","prefix":"index-"}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
