<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Result\SendTaskHeartbeatOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SendTaskHeartbeatOutputTest extends TestCase
{
    public function testSendTaskHeartbeatOutput(): void
    {
        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskHeartbeat.html
        $response = new SimpleMockedResponse('{
            "taskToken": "qwertyuiop"
        }');

        $this->expectNotToPerformAssertions();

        $client = new MockHttpClient($response);
        $result = new SendTaskHeartbeatOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
