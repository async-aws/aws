<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListStreamConsumersInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\Result\ListStreamConsumersOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListStreamConsumersOutputTest extends TestCase
{
    public function testListStreamConsumersOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreamConsumers.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListStreamConsumersOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new KinesisClient(), new ListStreamConsumersInput([]));

        // self::assertTODO(expected, $result->getConsumers());
        self::assertSame('changeIt', $result->getNextToken());
    }
}
