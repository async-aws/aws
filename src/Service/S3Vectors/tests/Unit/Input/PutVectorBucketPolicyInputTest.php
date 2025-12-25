<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\PutVectorBucketPolicyInput;

class PutVectorBucketPolicyInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutVectorBucketPolicyInput([
            'vectorBucketName' => 'change me',
            'vectorBucketArn' => 'change me',
            'policy' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_PutVectorBucketPolicy.html
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
