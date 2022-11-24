<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Input\ListObjectsRequest;
use AsyncAws\S3\Result\ListObjectsOutput;
use AsyncAws\S3\S3Client;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListObjectsOutputTest extends TestCase
{
    public function testListObjectsOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<Contents>
          <member>
            <ETag>"70ee1738b6b21e2c8a43f3a5ab0eee71"</ETag>
            <Key>example1.jpg</Key>
            <LastModified>2014-11-21T19:40:05.000Z</LastModified>
            <Owner>
              <DisplayName>myname</DisplayName>
              <ID>12345example25102679df27bb0ae12b3f85be6f290b936c4393484be31bebcc</ID>
            </Owner>
            <Size>11</Size>
            <StorageClass>STANDARD</StorageClass>
          </member>
          <member>
            <ETag>"9c8af9a76df052144598c115ef33e511"</ETag>
            <Key>example2.jpg</Key>
            <LastModified>2013-11-15T01:10:49.000Z</LastModified>
            <Owner>
              <DisplayName>myname</DisplayName>
              <ID>12345example25102679df27bb0ae12b3f85be6f290b936c4393484be31bebcc</ID>
            </Owner>
            <Size>713193</Size>
            <StorageClass>STANDARD</StorageClass>
          </member>
        </Contents>');

        $client = new MockHttpClient($response);
        $result = new ListObjectsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new S3Client(), new ListObjectsRequest([]));

        self::assertFalse($result->getIsTruncated());
        self::assertSame('changeIt', $result->getMarker());
        self::assertSame('changeIt', $result->getNextMarker());
        // self::assertTODO(expected, $result->getContents());
        self::assertSame('changeIt', $result->getName());
        self::assertSame('changeIt', $result->getPrefix());
        self::assertSame('changeIt', $result->getDelimiter());
        self::assertSame(1337, $result->getMaxKeys());
        // self::assertTODO(expected, $result->getCommonPrefixes());
        self::assertSame('changeIt', $result->getEncodingType());
    }
}
