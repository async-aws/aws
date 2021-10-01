<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Result\SendTaskSuccessOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SendTaskSuccessOutputTest extends TestCase
{
    public function testSendTaskSuccessOutput(): void
    {
        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskSuccess.html
        $response = new SimpleMockedResponse('{
            "output": "{"success": ":partyparrot:"}",
            "taskToken": "qwertyuiop"
        }');

        $this->expectNotToPerformAssertions();

        $client = new MockHttpClient($response);
        $result = new SendTaskSuccessOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
