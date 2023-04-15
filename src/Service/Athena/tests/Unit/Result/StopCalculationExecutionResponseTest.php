<?php

namespace AsyncAws\Athena\Tests\Unit\Result;

use AsyncAws\Athena\Result\StopCalculationExecutionResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StopCalculationExecutionResponseTest extends TestCase
{
    public function testStopCalculationExecutionResponse(): void
    {
        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_StopCalculationExecution.html
        $response = new SimpleMockedResponse('{
           "State": "CANCELING"
        }');

        $client = new MockHttpClient($response);
        $result = new StopCalculationExecutionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('CANCELING', $result->getState());
    }
}
