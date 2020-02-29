<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\PutObjectOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class PutObjectOutputTest extends TestCase
{
    public function testPutObjectOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<ETag>"6805f2cfc46c0f04559748bb039d69ae"</ETag>');

        $client = new MockHttpClient($response);
        $result = new PutObjectOutput($client->request('POST', 'http://localhost'), $client);

        self::assertSame('changeIt', $result->getExpiration());
        self::assertSame('changeIt', $result->getETag());
        self::assertSame('changeIt', $result->getServerSideEncryption());
        self::assertSame('changeIt', $result->getVersionId());
        self::assertSame('changeIt', $result->getSSECustomerAlgorithm());
        self::assertSame('changeIt', $result->getSSECustomerKeyMD5());
        self::assertSame('changeIt', $result->getSSEKMSKeyId());
        self::assertSame('changeIt', $result->getSSEKMSEncryptionContext());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
