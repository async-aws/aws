<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutPublicAccessBlockRequest;
use AsyncAws\S3\ValueObject\PublicAccessBlockConfiguration;

class PutPublicAccessBlockRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutPublicAccessBlockRequest([
            'Bucket' => 'public-access-block-bucket',
            'ContentMD5' => '098f6bcd4621d373cade4e832627b4f6',
            'ChecksumAlgorithm' => 'SHA256',
            'PublicAccessBlockConfiguration' => new PublicAccessBlockConfiguration([
                'BlockPublicAcls' => true,
                'IgnorePublicAcls' => false,
                'BlockPublicPolicy' => false,
                'RestrictPublicBuckets' => true,
            ]),
            'ExpectedBucketOwner' => '000000000000',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutPublicAccessBlock.html
        $expected = '
PUT /public-access-block-bucket?publicAccessBlock HTTP/1.1
Content-Type: application/xml
Content-MD5: 098f6bcd4621d373cade4e832627b4f6
x-amz-sdk-checksum-algorithm: SHA256
x-amz-expected-bucket-owner: 000000000000

<?xml version="1.0" encoding="UTF-8"?>
<PublicAccessBlockConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
   <BlockPublicAcls>true</BlockPublicAcls>
   <IgnorePublicAcls>false</IgnorePublicAcls>
   <BlockPublicPolicy>false</BlockPublicPolicy>
   <RestrictPublicBuckets>true</RestrictPublicBuckets>
</PublicAccessBlockConfiguration>
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
