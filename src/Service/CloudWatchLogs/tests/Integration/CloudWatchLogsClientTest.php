<?php

namespace AsyncAws\CloudWatchLogs\Tests\Integration;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Enum\OrderBy;
use AsyncAws\CloudWatchLogs\Input\CreateLogGroupRequest;
use AsyncAws\CloudWatchLogs\Input\CreateLogStreamRequest;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;

class CloudWatchLogsClientTest extends TestCase
{
    public function testCreateLogGroup(): void
    {
        $client = $this->getClient();

        $input = new CreateLogGroupRequest([
            'logGroupName' => 'my-log-group' . mt_rand(1000, 10000) . time(),
            'kmsKeyId' => 'arn:aws:kms:us-east-1:123456789012:key/abcd1234-a123-456a-a12b-a123b456c789',
        ]);
        $result = $client->CreateLogGroup($input);

        $result->resolve();
        self::assertEquals(200, $result->info()['status']);
    }

    public function testCreateLogStream(): void
    {
        $client = $this->getClient();

        $input = new CreateLogStreamRequest([
            'logGroupName' => 'my-log-group',
            'logStreamName' => 'my-log-stream' . mt_rand(1000, 10000) . time(),
        ]);
        $result = $client->createLogStream($input);

        self::expectException(ClientException::class);
        self::expectExceptionMessageMatches('/The specified log group does not exist/');

        $result->resolve();
    }

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

    public function testFilterLogEvents(): void
    {
        $client = $this->getClient();

        $input = new FilterLogEventsRequest([
            'logGroupName' => 'app-logs',
            'startTime' => 1337,
            'endTime' => 1338,
            'filterPattern' => 'ERROR',
            'limit' => 10,
        ]);
        $result = $client->filterLogEvents($input);

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
