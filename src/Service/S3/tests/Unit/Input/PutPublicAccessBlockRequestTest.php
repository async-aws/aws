<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutPublicAccessBlockRequest;
use AsyncAws\S3\ValueObject\PublicAccessBlockConfiguration;

class PutPublicAccessBlockRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutPublicAccessBlockRequest([
            'Bucket' => 'change me',
            'ContentMD5' => 'change me',
            'ChecksumAlgorithm' => 'change me',
            'PublicAccessBlockConfiguration' => new PublicAccessBlockConfiguration([
                'BlockPublicAcls' => false,
                'IgnorePublicAcls' => false,
                'BlockPublicPolicy' => false,
                'RestrictPublicBuckets' => false,
            ]),
            'ExpectedBucketOwner' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutPublicAccessBlock.html
        $expected = '
            PUT / HTTP/1.0
            Content-Type: application/xml

            <change>it</change>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
