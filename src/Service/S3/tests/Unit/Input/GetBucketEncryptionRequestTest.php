<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetBucketEncryptionRequest;

class GetBucketEncryptionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetBucketEncryptionRequest([
            'Bucket' => 'bucket-name',
            'ExpectedBucketOwner' => 'expected-owner',
        ]);

        $expected = '
GET /bucket-name?encryption HTTP/1.0
Content-Type: application/xml
x-amz-expected-bucket-owner: expected-owner
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
