<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\Result\DescribeLogStreamsResponse;
use AsyncAws\CloudWatchLogs\Result\PutLogEventsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CloudWatchLogsClientTest extends TestCase
{
    public function testDescribeLogStreams(): void
    {
        $client = new CloudWatchLogsClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeLogStreamsRequest([
            'logGroupName' => 'test_logGroupName',
        ]);
        $result = $client->DescribeLogStreams($input);

        self::assertInstanceOf(DescribeLogStreamsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutLogEvents(): void
    {
        $client = new CloudWatchLogsClient([], new NullProvider(), new MockHttpClient());

        $input = new PutLogEventsRequest([
            'logGroupName' => 'test_logGroupName',
            'logStreamName' => 'test_logStreamName',
            'logEvents' => [new InputLogEvent([
                'timestamp' => 1337,
                'message' => 'test_message',
            ])],
        ]);
        $result = $client->PutLogEvents($input);

        self::assertInstanceOf(PutLogEventsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
