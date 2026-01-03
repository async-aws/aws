<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\GetVectorBucketInput;

class GetVectorBucketInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetVectorBucketInput([
            'vectorBucketName' => 'my-bucket',
            'vectorBucketArn' => 'arn:aws:s3:us-east-1:123456789012:bucket/my-bucket',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_GetVectorBucket.html
        $expected = '
            POST /GetVectorBucket HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"vectorBucketName":"my-bucket","vectorBucketArn":"arn:aws:s3:us-east-1:123456789012:bucket/my-bucket"}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
