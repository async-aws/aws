<?php

declare(strict_types=1);

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\GetObjectOutput;
use PHPUnit\Framework\TestCase;

class GetObjectOutputTest extends TestCase
{
    public function testMetadata()
    {
        $headers = [
            'x-amz-id-2' => [0 => 'wHaofDPIxs4VoML+wxIjs/V+2Ke0B2bi6vDA6OPJctaYf2XgXJpdXCnuOTL0pPoQ48zMhL+fZXo='],
            'x-amz-request-id' => [0 => '29A72C65D02ED350'],
            'date' => [0 => 'Sat, 08 Feb 2020 15:58:09 GMT'],
            'last-modified' => [0 => 'Sat, 08 Feb 2020 15:55:28 GMT'],
            'etag' => [0 => '"9a0364b9e99bb480dd25e1f0284c8555"'],
            'x-amz-meta-tobias' => [0 => 'nyholm'],
            'accept-ranges' => [0 => 'bytes'],
            'content-type' => [0 => 'application/x-www-form-urlencoded'],
            'content-length' => [0 => '7'],
            'connection' => [0 => 'close'],
            'server' => [0 => 'AmazonS3'],
        ];
        $response = new SimpleMockedResponse('content', $headers);
        $result = new GetObjectOutput($response);

        $metadata = $result->getMetadata();
        self::assertCount(1, $metadata);
        self::assertArrayHasKey('x-amz-meta-tobias', $metadata);
        self::assertEquals('nyholm', $metadata['x-amz-meta-tobias']);
    }
}
