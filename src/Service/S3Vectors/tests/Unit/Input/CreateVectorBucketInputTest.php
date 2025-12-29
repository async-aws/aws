<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\CreateVectorBucketInput;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;

class CreateVectorBucketInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new CreateVectorBucketInput([
            'vectorBucketName' => 'change me',
            'encryptionConfiguration' => new EncryptionConfiguration([
                'sseType' => 'change me',
                'kmsKeyArn' => 'change me',
            ]),
            'tags' => ['change me' => 'change me'],
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_CreateVectorBucket.html
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
