<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\ListPartsOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class ListPartsOutputTest extends TestCase
{
    public function testListPartsOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<Initiator>
          <DisplayName>owner-display-name</DisplayName>
          <ID>examplee7a2f25102679df27bb0ae12b3f85be6f290b936c4393484be31bebcc</ID>
        </Initiator>');

        $client = new MockHttpClient($response);
        $result = new ListPartsOutput(new Response($client->request('POST', 'http://localhost'), $client));

        // self::assertTODO(expected, $result->getAbortDate());
        self::assertSame('changeIt', $result->getAbortRuleId());
        self::assertSame('changeIt', $result->getBucket());
        self::assertSame('changeIt', $result->getKey());
        self::assertSame('changeIt', $result->getUploadId());
        self::assertSame(1337, $result->getPartNumberMarker());
        self::assertSame(1337, $result->getNextPartNumberMarker());
        self::assertSame(1337, $result->getMaxParts());
        self::assertFalse($result->getIsTruncated());
        // self::assertTODO(expected, $result->getParts());
        // self::assertTODO(expected, $result->getInitiator());
        // self::assertTODO(expected, $result->getOwner());
        self::assertSame('changeIt', $result->getStorageClass());
        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
