<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\HeadObjectRequest;

class HeadObjectRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new HeadObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo.jpg',
            'VersionId' => 'abc123',
        ]);

        self::assertEquals('/my-bucket/foo.jpg', $input->requestUri());
        self::assertEmpty($input->requestBody());

        $query = $input->requestQuery();
        self::assertArrayHasKey('versionId', $query);
        self::assertEquals('abc123', $query['versionId']);
    }
}
