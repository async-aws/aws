<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetBucketVersioningRequest;

class GetBucketVersioningRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetBucketVersioningRequest([
            'Bucket' => 'Bucket',
            'ExpectedBucketOwner' => 'ExpectedBucketOwner',
        ]);

        // see example-1.json from SDK
        $expected = 'GET /Bucket?versioning HTTP/1.1
            x-amz-expected-bucket-owner: ExpectedBucketOwner
            content-type: application/xml
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
