<?php

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Result\StartMessageMoveTaskResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartMessageMoveTaskResultTest extends TestCase
{
    public function testStartMessageMoveTaskResult(): void
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_StartMessageMoveTask.html
        $response = new SimpleMockedResponse(<<<JSON
        {
            "TaskHandle": "eyJ0YXNrSWQiOiJkYzE2OWUwNC0wZTU1LTQ0ZDItYWE5MC1jMDgwY2ExZjM2ZjciLCJzb3VyY2VBcm4iOiJhcm46YXdzOnNxczp1cy1lYXN0LTE6MTc3NzE1MjU3NDM2Ok15RGVhZExldHRlclF1ZXVlIn0="
        }
        JSON);

        $client = new MockHttpClient($response);
        $result = new StartMessageMoveTaskResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('eyJ0YXNrSWQiOiJkYzE2OWUwNC0wZTU1LTQ0ZDItYWE5MC1jMDgwY2ExZjM2ZjciLCJzb3VyY2VBcm4iOiJhcm46YXdzOnNxczp1cy1lYXN0LTE6MTc3NzE1MjU3NDM2Ok15RGVhZExldHRlclF1ZXVlIn0=', $result->getTaskHandle());
    }
}
