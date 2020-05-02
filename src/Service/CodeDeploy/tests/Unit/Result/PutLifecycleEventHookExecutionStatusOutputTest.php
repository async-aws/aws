<?php

namespace AsyncAws\CodeDeploy\Tests\Unit\Result;

use AsyncAws\CodeDeploy\Result\PutLifecycleEventHookExecutionStatusOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutLifecycleEventHookExecutionStatusOutputTest extends TestCase
{
    public function testPutLifecycleEventHookExecutionStatusOutput(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_PutLifecycleEventHookExecutionStatus.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new PutLifecycleEventHookExecutionStatusOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('changeIt', $result->getlifecycleEventHookExecutionId());
    }
}
