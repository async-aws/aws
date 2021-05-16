<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\ListShardsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListShardsOutputTest extends TestCase
{
    public function testListShardsOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListShards.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListShardsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getShards());
        self::assertSame('changeIt', $result->getNextToken());
    }
}
