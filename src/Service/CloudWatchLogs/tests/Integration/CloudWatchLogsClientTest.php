<?php

namespace AsyncAws\CloudWatchLogs\Tests\Integration;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Enum\OrderBy;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;

class CloudWatchLogsClientTest extends TestCase
{
    public function testDescribeLogStreams(): void
    {
        $client = $this->getClient();

        $input = new DescribeLogStreamsRequest([
            'logGroupName' => 'app-logs',
            'orderBy' => OrderBy::LAST_EVENT_TIME,
            'descending' => false,
            'limit' => 10,
        ]);
        $result = $client->DescribeLogStreams($input);

        self::expectException(ClientException::class);
        self::expectExceptionMessageMatches('/The specified log group does not exist/');

        $result->resolve();
    }

    public function testPutLogEvents(): void
    {
        $client = $this->getClient();

        $input = new PutLogEventsRequest([
            'logGroupName' => 'app-logs',
            'logStreamName' => 'front-stream',
            'logEvents' => [new InputLogEvent([
                'timestamp' => time(),
                'message' => 'something goes wrong',
            ])],
        ]);
        $result = $client->PutLogEvents($input);

        self::expectException(ClientException::class);
        self::expectExceptionMessageMatches('/The specified log group does not exist/');

        $result->resolve();
    }

    private function getClient(): CloudWatchLogsClient
    {
        return new CloudWatchLogsClient([
            'endpoint' => 'http://localhost:4568',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
