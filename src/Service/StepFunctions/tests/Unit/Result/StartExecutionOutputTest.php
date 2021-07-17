<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Result\StartExecutionOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartExecutionOutputTest extends TestCase
{
    public function testStartExecutionOutput(): void
    {
        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_StartExecution.html
        $response = new SimpleMockedResponse('{
           "executionArn": "arn:foo:bar",
           "startDate": "1234567.123456"
        }');

        $client = new MockHttpClient($response);
        $result = new StartExecutionOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:foo:bar', $result->getExecutionArn());
        self::assertSame(1234567, $result->getStartDate()->getTimestamp());
    }
}
