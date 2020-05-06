<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Result;

use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutLogEventsResponseTest extends TestCase
{
    public function testPutLogEventsResponse(): void
    {
        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html
        $response = new SimpleMockedResponse('{
           "nextSequenceToken": "test_nextSequenceToken",
           "rejectedLogEventsInfo": {
              "expiredLogEventEndIndex": 1000,
              "tooNewLogEventStartIndex": 1001,
              "tooOldLogEventEndIndex": 1002
           }
        }');

        $client = new MockHttpClient($response);
        $result = new PutLogEventsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('test_nextSequenceToken', $result->getNextSequenceToken());
        self::assertSame(1000, $result->getRejectedLogEventsInfo()->getExpiredLogEventEndIndex());
        self::assertSame(1001, $result->getRejectedLogEventsInfo()->getTooNewLogEventStartIndex());
        self::assertSame(1002, $result->getRejectedLogEventsInfo()->getTooOldLogEventEndIndex());
    }
}
