<?php

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Result\CancelMessageMoveTaskResult;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CancelMessageMoveTaskResultTest extends TestCase
{
    public function testCancelMessageMoveTaskResult(): void
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CancelMessageMoveTask.html
        $response = new SimpleMockedResponse('{
            "ApproximateNumberOfMessagesMoved": 5
        }');

        $client = new MockHttpClient($response);
        $result = new CancelMessageMoveTaskResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(5, $result->getApproximateNumberOfMessagesMoved());
    }
}
