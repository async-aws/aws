<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\CreateIndexInput;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\MetadataConfiguration;

class CreateIndexInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateIndexInput([
            'vectorBucketName' => 'my-bucket',
            'vectorBucketArn' => 'arn:aws:s3:us-east-1:123456789012:bucket/my-bucket',
            'indexName' => 'my-index',
            'dataType' => \AsyncAws\S3Vectors\Enum\DataType::FLOAT_32,
            'dimension' => 128,
            'distanceMetric' => \AsyncAws\S3Vectors\Enum\DistanceMetric::COSINE,
            'metadataConfiguration' => new MetadataConfiguration([
                'nonFilterableMetadataKeys' => [],
            ]),
            'encryptionConfiguration' => new EncryptionConfiguration([
                'sseType' => 'AES256',
                'kmsKeyArn' => 'arn:aws:kms:us-east-1:123:key/abc',
            ]),
            'tags' => ['env' => 'dev'],
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_CreateIndex.html
        $expected = '
            POST /CreateIndex HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {"vectorBucketName":"my-bucket","vectorBucketArn":"arn:aws:s3:us-east-1:123456789012:bucket/my-bucket","indexName":"my-index","dataType":"float32","dimension":128,"distanceMetric":"cosine","metadataConfiguration":{"nonFilterableMetadataKeys":[]},"encryptionConfiguration":{"sseType":"AES256","kmsKeyArn":"arn:aws:kms:us-east-1:123:key/abc"},"tags":{"env":"dev"}}
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
