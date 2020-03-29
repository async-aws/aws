<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\ListMultipartUploadsOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class ListMultipartUploadsOutputTest extends TestCase
{
    public function testListMultipartUploadsOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<Bucket>acl1</Bucket>');

        $client = new MockHttpClient($response);
        $result = new ListMultipartUploadsOutput(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('changeIt', $result->getBucket());
        self::assertSame('changeIt', $result->getKeyMarker());
        self::assertSame('changeIt', $result->getUploadIdMarker());
        self::assertSame('changeIt', $result->getNextKeyMarker());
        self::assertSame('changeIt', $result->getPrefix());
        self::assertSame('changeIt', $result->getDelimiter());
        self::assertSame('changeIt', $result->getNextUploadIdMarker());
        self::assertSame(1337, $result->getMaxUploads());
        self::assertFalse($result->getIsTruncated());
        // self::assertTODO(expected, $result->getUploads());
        // self::assertTODO(expected, $result->getCommonPrefixes());
        self::assertSame('changeIt', $result->getEncodingType());
    }
}
