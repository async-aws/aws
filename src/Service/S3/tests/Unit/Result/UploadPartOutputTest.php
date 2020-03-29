<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\UploadPartOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class UploadPartOutputTest extends TestCase
{
    public function testUploadPartOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<ETag>"d8c2eafd90c266e19ab9dcacc479f8af"</ETag>');

        $client = new MockHttpClient($response);
        $result = new UploadPartOutput(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('changeIt', $result->getServerSideEncryption());
        self::assertSame('changeIt', $result->getETag());
        self::assertSame('changeIt', $result->getSSECustomerAlgorithm());
        self::assertSame('changeIt', $result->getSSECustomerKeyMD5());
        self::assertSame('changeIt', $result->getSSEKMSKeyId());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
