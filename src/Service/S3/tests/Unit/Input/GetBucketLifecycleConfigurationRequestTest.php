<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetBucketLifecycleConfigurationRequest;

class GetBucketLifecycleConfigurationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetBucketLifecycleConfigurationRequest([
            'Bucket' => 'my-bucket',
            'ExpectedBucketOwner' => '123456789012',
        ]);

        $expected = '
GET /my-bucket?lifecycle HTTP/1.0
Content-Type: application/xml
x-amz-expected-bucket-owner: 123456789012
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
