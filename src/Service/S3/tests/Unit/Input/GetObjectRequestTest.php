<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\GetObjectRequest;

class GetObjectRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObject.html
     */
    public function testRequestBody(): void
    {
        $input = new GetObjectRequest([
            'Bucket' => 'my-bucket',
            'Key' => 'foo.jpg',
            'VersionId' => 'abc123',
        ]);

        self::assertEquals('/my-bucket/foo.jpg', $input->request()->getUri());

        $query = $input->request()->getQuery();
        self::assertArrayHasKey('versionId', $query);
        self::assertEquals('abc123', $query['versionId']);
    }
}
