<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\CreateMultipartUploadOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateMultipartUploadOutputTest extends TestCase
{
    public function testCreateMultipartUploadOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<Bucket>examplebucket</Bucket>');

        $client = new MockHttpClient($response);
        $result = new CreateMultipartUploadOutput(new Response($client->request('POST', 'http://localhost'), $client));

        // self::assertTODO(expected, $result->getAbortDate());
        self::assertSame('changeIt', $result->getAbortRuleId());
        self::assertSame('changeIt', $result->getBucket());
        self::assertSame('changeIt', $result->getKey());
        self::assertSame('changeIt', $result->getUploadId());
        self::assertSame('changeIt', $result->getServerSideEncryption());
        self::assertSame('changeIt', $result->getSSECustomerAlgorithm());
        self::assertSame('changeIt', $result->getSSECustomerKeyMD5());
        self::assertSame('changeIt', $result->getSSEKMSKeyId());
        self::assertSame('changeIt', $result->getSSEKMSEncryptionContext());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
