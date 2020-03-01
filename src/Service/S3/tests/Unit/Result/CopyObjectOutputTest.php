<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\CopyObjectOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class CopyObjectOutputTest extends TestCase
{
    public function testCopyObjectOutput(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
        <CopyObjectResult>
          <ETag>"6805f2cfc46c0f04559748bb039d69ae"</ETag>
          <LastModified>2016-12-15T17:38:53.000Z</LastModified>
        </CopyObjectResult>');

        $client = new MockHttpClient($response);
        $result = new CopyObjectOutput($client->request('POST', 'http://localhost'), $client);

        self::assertNotNull($result->getCopyObjectResult());
        self::assertEquals('"6805f2cfc46c0f04559748bb039d69ae"', $result->getCopyObjectResult()->getETag());
        self::assertEquals(new \DateTimeImmutable('2016-12-15T17:38:53.000Z'), $result->getCopyObjectResult()->getLastModified());
    }
}
