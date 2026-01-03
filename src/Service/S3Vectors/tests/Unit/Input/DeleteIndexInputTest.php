<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\DeleteIndexInput;

class DeleteIndexInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteIndexInput([
            'vectorBucketName' => 'my-bucket',
            'indexName' => 'my-index',
            'indexArn' => 'arn:aws:s3:us-east-1:123456789012:index/my-index',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_DeleteIndex.html
        $expected = '
            POST /DeleteIndex HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"vectorBucketName":"my-bucket","indexName":"my-index","indexArn":"arn:aws:s3:us-east-1:123456789012:index/my-index"}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
