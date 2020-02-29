<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\GetObjectOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class GetObjectOutputTest extends TestCase
{
    public function testGetObjectOutput(): void
    {
        $headers = [
            'x-amz-id-2' => 'eE1ciiorDCob2JGCZ0Y8VLKuFcRXcjKfz1kxgN2xnSpQG+yN+vmXjpTPQ0QKbRlc6tVidz0Ntd8=',
            'x-amz-request-id' => '345FC7FD1AE48617',
            'date' => 'Sun, 23 Feb 2020 08:57:06 GMT',
            'last-modified' => 'Sun, 23 Feb 2020 08:57:05 GMT',
            'etag' => '"98bf7d8c15784f0a3d63204441e1e2aa"',
            'accept-ranges' => 'bytes',
            'content-type' => 'text/plain',
            'content-length' => '0',
            'server' => 'AmazonS3',
        ];
        $response = new SimpleMockedResponse('', $headers);

        $client = new MockHttpClient($response);
        $result = new GetObjectOutput($client->request('POST', 'http://localhost'), $client);

        // self::assertTODO(expected, $result->getBody());
        self::assertStringContainsString('98bf7d8c15784f0a3d63204441e1e2aa', $result->getETag());

        self::assertNull($result->getDeleteMarker());
        self::assertEquals('bytes', $result->getAcceptRanges());
        self::assertEquals(1582448225, $result->getLastModified()->getTimestamp());
    }

    public function testMetadata()
    {
        $headers = [
            'x-amz-id-2' => 'wHaofDPIxs4VoML+wxIjs/V+2Ke0B2bi6vDA6OPJctaYf2XgXJpdXCnuOTL0pPoQ48zMhL+fZXo=',
            'x-amz-request-id' => '29A72C65D02ED350',
            'date' => 'Sat, 08 Feb 2020 15:58:09 GMT',
            'last-modified' => 'Sat, 08 Feb 2020 15:55:28 GMT',
            'etag' => '"9a0364b9e99bb480dd25e1f0284c8555"',
            'x-amz-meta-tobias' => 'nyholm',
            'accept-ranges' => 'bytes',
            'content-type' => 'application/x-www-form-urlencoded',
            'content-length' => '7',
            'connection' => 'close',
            'server' => 'AmazonS3',
        ];
        $response = new SimpleMockedResponse('content', $headers);
        $client = new MockHttpClient($response);
        $result = new GetObjectOutput($client->request('POST', 'http://localhost'), $client);

        $metadata = $result->getMetadata();
        self::assertCount(1, $metadata);
        self::assertArrayHasKey('x-amz-meta-tobias', $metadata);
        self::assertEquals('nyholm', $metadata['x-amz-meta-tobias']);
    }
}
