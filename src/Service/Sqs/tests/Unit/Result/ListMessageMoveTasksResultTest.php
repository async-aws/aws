<?php

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Result\ListMessageMoveTasksResult;
use AsyncAws\Sqs\ValueObject\ListMessageMoveTasksResultEntry;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListMessageMoveTasksResultTest extends TestCase
{
    public function testListMessageMoveTasksResult(): void
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListMessageMoveTasks.html
        $response = new SimpleMockedResponse(<<<JSON
        {
          "Results": [
                {
                    "ApproximateNumberOfMessagesMoved": 50,
                    "ApproximateNumberOfMessagesToMove": 0,
                    "DestinationArn": "arn:aws:sqs:us-east-1:555555555555:MySourceQueue",
                    "MaxNumberOfMessagesPerSecond": 20,
                    "SourceArn": "arn:aws:sqs:us-east-1:555555555555:MyDeadLetterQueue",
                    "StartedTimestamp": 1684429053010,
                    "Status": "COMPLETED"
                }
            ]
        }
        JSON);

        $client = new MockHttpClient($response);
        $result = new ListMessageMoveTasksResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        /** @var ListMessageMoveTasksResultEntry $entry */
        $entry = $result->getResults()[0];
        self::assertEquals(50, $entry->getApproximateNumberOfMessagesMoved());
        self::assertEquals(0, $entry->getApproximateNumberOfMessagesToMove());
        self::assertEquals('arn:aws:sqs:us-east-1:555555555555:MySourceQueue', $entry->getDestinationArn());
        self::assertEquals(20, $entry->getMaxNumberOfMessagesPerSecond());
        self::assertEquals('arn:aws:sqs:us-east-1:555555555555:MyDeadLetterQueue', $entry->getSourceArn());
        self::assertEquals(1684429053010, $entry->getStartedTimestamp());
        self::assertEquals('COMPLETED', $entry->getStatus());
    }
}
