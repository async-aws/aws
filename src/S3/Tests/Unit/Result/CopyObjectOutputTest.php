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
        self::markTestIncomplete('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<CopyObjectResult>
          <ETag>"6805f2cfc46c0f04559748bb039d69ae"</ETag>
          <LastModified>2016-12-15T17:38:53.000Z</LastModified>
        </CopyObjectResult>');

        $client = new MockHttpClient($response);
        $result = new CopyObjectOutput($client->request('POST', 'http://localhost'), $client);

        // self::assertTODO(expected, $result->getCopyObjectResult());
        self::assertSame('changeIt', $result->getExpiration());
        self::assertSame('changeIt', $result->getCopySourceVersionId());
        self::assertSame('changeIt', $result->getVersionId());
        self::assertSame('changeIt', $result->getServerSideEncryption());
        self::assertSame('changeIt', $result->getSSECustomerAlgorithm());
        self::assertSame('changeIt', $result->getSSECustomerKeyMD5());
        self::assertSame('changeIt', $result->getSSEKMSKeyId());
        self::assertSame('changeIt', $result->getSSEKMSEncryptionContext());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
