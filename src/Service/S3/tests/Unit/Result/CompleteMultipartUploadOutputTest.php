<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\CompleteMultipartUploadOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class CompleteMultipartUploadOutputTest extends TestCase
{
    public function testCompleteMultipartUploadOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<Bucket>acexamplebucket</Bucket>');

        $client = new MockHttpClient($response);
        $result = new CompleteMultipartUploadOutput(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('changeIt', $result->getLocation());
        self::assertSame('changeIt', $result->getBucket());
        self::assertSame('changeIt', $result->getKey());
        self::assertSame('changeIt', $result->getExpiration());
        self::assertSame('changeIt', $result->getETag());
        self::assertSame('changeIt', $result->getServerSideEncryption());
        self::assertSame('changeIt', $result->getVersionId());
        self::assertSame('changeIt', $result->getSSEKMSKeyId());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
