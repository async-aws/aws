<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CopyObjectRequest;

class CopyObjectRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html#API_CopyObject_Examples
     */
    public function testRequest(): void
    {
        $input = new CopyObjectRequest([
            'Key' => 'my-second-image.jpg',
            'Bucket' => 'my-bucket',
            'ContentType' => 'image/jpg',
            'CopySource' => '/bucket/my-image.jpg',
        ]);

        $expected = '
            PUT /my-bucket/my-second-image.jpg HTTP/1.0
            Content-Type: image/jpg
            x-amz-copy-source: /bucket/my-image.jpg
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
