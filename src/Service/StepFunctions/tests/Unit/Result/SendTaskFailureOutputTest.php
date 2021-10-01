<?php

namespace AsyncAws\StepFunctions\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\StepFunctions\Result\SendTaskFailureOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class SendTaskFailureOutputTest extends TestCase
{
    public function testSendTaskFailureOutput(): void
    {
        // see https://docs.aws.amazon.com/step-functions/latest/apireference/API_SendTaskFailure.html
        $response = new SimpleMockedResponse('{
            "cause": "Crash!",
            "error": "err_foo",
            "taskToken": "qwertyuiop"
        }');

        $this->expectNotToPerformAssertions();

        $client = new MockHttpClient($response);
        $result = new SendTaskFailureOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
    }
}
