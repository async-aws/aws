<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Result\StopExecutionOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StopExecutionOutputTest extends TestCase
{
    public function testStopExecutionOutput(): void
    {
        // see https://docs.aws.amazon.com/stepfunctions/latest/APIReference/API_StopExecution.html
        $response = new SimpleMockedResponse('{
            "executionArn": "arn:foo:bar",
            "stopDate": "1234567.123456"
        }');

        $client = new MockHttpClient($response);
        $result = new StopExecutionOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(1234567, $result->getStopDate()->getTimestamp());
    }
}
