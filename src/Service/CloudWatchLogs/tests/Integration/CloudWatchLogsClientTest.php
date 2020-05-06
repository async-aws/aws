<?php

namespace AsyncAws\CloudWatchLogs\Tests\Integration;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\Input\PutLogEventsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\InputLogEvent;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CloudWatchLogsClientTest extends TestCase
{
    public function testDescribeLogStreams(): void
    {
        $client = $this->getClient();

        $input = new DescribeLogStreamsRequest([
            'logGroupName' => 'change me',
            'logStreamNamePrefix' => 'change me',
            'orderBy' => 'change me',
            'descending' => false,
            'nextToken' => 'change me',
            'limit' => 1337,
        ]);
        $result = $client->DescribeLogStreams($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getnextToken());
    }

    public function testPutLogEvents(): void
    {
        $client = $this->getClient();

        $input = new PutLogEventsRequest([
            'logGroupName' => 'change me',
            'logStreamName' => 'change me',
            'logEvents' => [new InputLogEvent([
                'timestamp' => 1337,
                'message' => 'change me',
            ])],
            'sequenceToken' => 'change me',
        ]);
        $result = $client->PutLogEvents($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getnextSequenceToken());
    }

    private function getClient(): CloudWatchLogsClient
    {
        self::markTestSkipped('There is no docker image available for CloudWatchLogs.');

        return new CloudWatchLogsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
