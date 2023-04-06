<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\StopQueryExecutionOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StopQueryExecutionOutputTest extends TestCase
{
    public function testStopQueryExecutionOutput(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StopQueryExecution.html
        $response = new SimpleMockedResponse();

        $client = new MockHttpClient($response);
        $result = new StopQueryExecutionOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertFalse($result->info()['resolved']);
    }
}
