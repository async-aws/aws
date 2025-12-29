<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\CreateVectorBucketInput;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;

class CreateVectorBucketInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateVectorBucketInput([
            'vectorBucketName' => 'my-bucket',
            'encryptionConfiguration' => new EncryptionConfiguration([
                'sseType' => 'AES256',
                'kmsKeyArn' => 'arn:aws:kms:us-east-1:123:key/abc',
            ]),
            'tags' => ['env' => 'dev'],
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_CreateVectorBucket.html
        $expected = '
            POST /CreateVectorBucket HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"vectorBucketName":"my-bucket","encryptionConfiguration":{"sseType":"AES256","kmsKeyArn":"arn:aws:kms:us-east-1:123:key/abc"},"tags":{"env":"dev"}}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
