<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\GetBucketTaggingOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetBucketTaggingOutputTest extends TestCase
{
    public function testGetBucketTaggingOutput(): void
    {
        self::fail('Not implemented');

        // see example-1.json from SDK
        $response = new SimpleMockedResponse('<TagSet>
          <member>
            <Key>key1</Key>
            <Value>value1</Value>
          </member>
          <member>
            <Key>key2</Key>
            <Value>value2</Value>
          </member>
        </TagSet>');

        $client = new MockHttpClient($response);
        $result = new GetBucketTaggingOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getTagSet());
    }
}
