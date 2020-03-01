<?php

namespace AsyncAws\S3\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\CopyObjectRequest;

class CopyObjectRequestTest extends TestCase
{
    /**
     * @see https://docs.aws.amazon.com/AmazonS3/latest/API/API_CopyObject.html#API_CopyObject_Examples
     */
    public function testRequestBody(): void
    {
        $input = new CopyObjectRequest([
            'Key' => 'my-second-image.jpg',
            'Bucket' => 'my-bucket',
            'ContentType' => 'image/jpg',
            'CopySource' => '/bucket/my-image.jpg',
        ]);

        self::assertEmpty($input->requestBody());
        $requestHeaders = $input->requestHeaders();
        self::assertEquals('/bucket/my-image.jpg', $requestHeaders['x-amz-copy-source']);

        self::assertEquals('/my-bucket/my-second-image.jpg', $input->requestUri());
    }
}
