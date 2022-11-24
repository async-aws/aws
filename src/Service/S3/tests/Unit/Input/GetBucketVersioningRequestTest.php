<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetBucketVersioningRequest;

class GetBucketVersioningRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetBucketVersioningRequest([
            'Bucket' => 'change me',
            'ExpectedBucketOwner' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/xml

            <Bucket>examplebucket</Bucket>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
