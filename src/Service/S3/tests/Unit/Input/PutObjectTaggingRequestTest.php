<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutObjectTaggingRequest;
use AsyncAws\S3\ValueObject\Tag;
use AsyncAws\S3\ValueObject\Tagging;

class PutObjectTaggingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutObjectTaggingRequest([
            'Bucket' => 'examplebucket',
            'Key' => 'baz/HappyFace.jpg',
            'ContentMD5' => 'NDhmNGNlNzhjZDAzODJlZjY1YTFmNjQzYjMxNmExZDg1YzM0MzZmNzUzNTVhNDBmYzFmOWE2Y2FjNTkyYWYxYQ==',
            'ChecksumAlgorithm' => 'SHA256',
            'ExpectedBucketOwner' => '0123456789',
            'Tagging' => new Tagging([
                'TagSet' => [new Tag([
                    'Key' => 'expire-after-30-days',
                    'Value' => '1',
                ])],
            ]),
        ]);

        $expected = '
PUT /examplebucket/baz/HappyFace.jpg?tagging HTTP/1.0
Content-Type: application/xml
Content-md5: NDhmNGNlNzhjZDAzODJlZjY1YTFmNjQzYjMxNmExZDg1YzM0MzZmNzUzNTVhNDBmYzFmOWE2Y2FjNTkyYWYxYQ==
x-amz-expected-bucket-owner: 0123456789
x-amz-sdk-checksum-algorithm: SHA256

<?xml version="1.0" encoding="UTF-8"?>
<Tagging xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <TagSet>
        <Tag>
            <Key>expire-after-30-days</Key>
            <Value>1</Value>
        </Tag>
    </TagSet>
</Tagging>
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
