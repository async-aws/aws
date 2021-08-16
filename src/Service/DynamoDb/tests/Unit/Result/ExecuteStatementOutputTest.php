<?php

namespace AsyncAws\DynamoDb\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\DynamoDb\Result\ExecuteStatementOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ExecuteStatementOutputTest extends TestCase
{
    public function testExecuteStatementOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/dynamodb/latest/APIReference/API_ExecuteStatement.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ExecuteStatementOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        // self::assertTODO(expected, $result->getItems());
        self::assertSame('changeIt', $result->getNextToken());
    }
}
