<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutBucketEncryptionRequest;
use AsyncAws\S3\ValueObject\ServerSideEncryptionByDefault;
use AsyncAws\S3\ValueObject\ServerSideEncryptionConfiguration;
use AsyncAws\S3\ValueObject\ServerSideEncryptionRule;

class PutBucketEncryptionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutBucketEncryptionRequest([
            'Bucket' => 'change me',
            'ContentMD5' => 'change me',
            'ChecksumAlgorithm' => 'change me',
            'ServerSideEncryptionConfiguration' => new ServerSideEncryptionConfiguration([
                'Rules' => [new ServerSideEncryptionRule([
                    'ApplyServerSideEncryptionByDefault' => new ServerSideEncryptionByDefault([
                        'SSEAlgorithm' => 'change me',
                        'KMSMasterKeyID' => 'change me',
                    ]),
                    'BucketKeyEnabled' => false,
                ])],
            ]),
            'ExpectedBucketOwner' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketEncryption.html
        $expected = '
            PUT / HTTP/1.0
            Content-Type: application/xml

            <change>it</change>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
