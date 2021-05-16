<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListStreamsInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\Result\ListStreamsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListStreamsOutputTest extends TestCase
{
    public function testListStreamsOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreams.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListStreamsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new KinesisClient(), new ListStreamsInput([]));

        // self::assertTODO(expected, $result->getStreamNames());
        self::assertFalse($result->getHasMoreStreams());
    }
}
