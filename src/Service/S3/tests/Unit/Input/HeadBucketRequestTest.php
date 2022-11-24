<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\HeadBucketRequest;

class HeadBucketRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new HeadBucketRequest([
            'Bucket' => 'change me',
            'ExpectedBucketOwner' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            HEAD / HTTP/1.0
            Content-Type: application/xml

            <Bucket>acl1</Bucket>
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
