<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\DeleteBucketRequest;

class DeleteBucketRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteBucketRequest([
            'Bucket' => 'my_bucket',
        ]);

        $expected = '
DELETE /my_bucket HTTP/1.0
Content-Type: application/xml
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
