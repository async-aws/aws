<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\StartQueryExecutionOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartQueryExecutionOutputTest extends TestCase
{
    public function testStartQueryExecutionOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartQueryExecution.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new StartQueryExecutionOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getQueryExecutionId());
    }
}
