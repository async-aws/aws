<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Result;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest;
use AsyncAws\CloudWatchLogs\Result\FilterLogEventsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\FilteredLogEvent;
use AsyncAws\CloudWatchLogs\ValueObject\SearchedLogStream;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class FilterLogEventsResponseTest extends TestCase
{
    public function testFilterLogEventsResponse(): void
    {
        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_FilterLogEvents.html
        $response = new SimpleMockedResponse('{
           "events": [
              {
                 "eventId": "36642742439302039892221577788596614866277653557049884672",
                 "ingestionTime": 1643117400988,
                 "logStreamName": "my-log-stream-1",
                 "message": "production.ERROR something",
                 "timestamp": 1643117398683
              },
              {
                 "eventId": "36642742439302039892221577788596614866277653557049884673",
                 "ingestionTime": 1643117400988,
                 "logStreamName": "my-log-stream-1",
                 "message": "production.ERROR something else",
                 "timestamp": 1643117398684
              }
           ],
           "nextToken": "foobar",
           "searchedLogStreams": [
              {
                 "logStreamName": "my-log-stream-1",
                 "searchedCompletely": false
              }
           ]
        }');

        $client = new MockHttpClient($response);
        $request = new FilterLogEventsRequest(['logGroupName' => 'app-log']);
        $result = new FilterLogEventsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new CloudWatchLogsClient(), $request);

        /** @var FilteredLogEvent[] $events */
        $events = iterator_to_array($result->getEvents(true));
        self::assertCount(2, $events);
        self::assertEquals('production.ERROR something', $events[0]->getMessage());
        self::assertEquals('36642742439302039892221577788596614866277653557049884672', $events[0]->getEventId());
        self::assertEquals('1643117398683', $events[0]->getTimestamp());
        self::assertEquals('my-log-stream-1', $events[0]->getLogStreamName());

        /** @var SearchedLogStream[] $searchedLogStreams */
        $searchedLogStreams = iterator_to_array($result->getSearchedLogStreams(true));
        self::assertCount(1, $searchedLogStreams);
        self::assertEquals('my-log-stream-1', $searchedLogStreams[0]->getLogStreamName());
        self::assertFalse($searchedLogStreams[0]->getSearchedCompletely());

        self::assertSame('foobar', $result->getnextToken());
    }
}
