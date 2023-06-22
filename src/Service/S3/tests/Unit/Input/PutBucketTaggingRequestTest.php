<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\PutBucketTaggingRequest;
use AsyncAws\S3\ValueObject\Tag;
use AsyncAws\S3\ValueObject\Tagging;

class PutBucketTaggingRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutBucketTaggingRequest([
            'Bucket' => 'my-website-assets-bucket',
            'ContentMD5' => 'NDhmNGNlNzhjZDAzODJlZjY1YTFmNjQzYjMxNmExZDg1YzM0MzZmNzUzNTVhNDBmYzFmOWE2Y2FjNTkyYWYxYQ==',
            'ChecksumAlgorithm' => 'SHA256',
            'Tagging' => new Tagging([
                'TagSet' => [
                    new Tag([
                        'Key' => 'environment',
                        'Value' => 'production',
                    ]),
                    new Tag([
                        'Key' => 'project-name',
                        'Value' => 'unicorn',
                    ]),
                ],
            ]),
            'ExpectedBucketOwner' => '0123456789',
        ]);

        // see example-1.json from SDK
        $expected = '
            PUT /my-website-assets-bucket?tagging HTTP/1.1
            Content-Type: application/xml
            Content-md5: NDhmNGNlNzhjZDAzODJlZjY1YTFmNjQzYjMxNmExZDg1YzM0MzZmNzUzNTVhNDBmYzFmOWE2Y2FjNTkyYWYxYQ==
            x-amz-expected-bucket-owner: 0123456789
            x-amz-sdk-checksum-algorithm: SHA256

            <?xml version="1.0" encoding="UTF-8"?>
            <Tagging xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
              <TagSet>
                <Tag>
                  <Key>environment</Key>
                  <Value>production</Value>
                </Tag>
                <Tag>
                  <Key>project-name</Key>
                  <Value>unicorn</Value>
                </Tag>
              </TagSet>
            </Tagging>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
