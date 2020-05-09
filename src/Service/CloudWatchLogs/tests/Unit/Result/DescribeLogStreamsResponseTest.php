<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Result;

use AsyncAws\CloudWatchLogs\Result\DescribeLogStreamsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\LogStream;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeLogStreamsResponseTest extends TestCase
{
    public function testDescribeLogStreamsResponse(): void
    {
        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogStreams.html
        $response = new SimpleMockedResponse('{
            "logStreams": [
                {
                    "storedBytes": 0,
                    "arn": "arn:aws:logs:us-east-1:123456789012:log-group:my-log-group-1:log-stream:my-log-stream-1",
                    "creationTime": 1393545600000,
                    "firstEventTimestamp": 1393545600000,
                    "lastEventTimestamp": 1393567800000,
                    "lastIngestionTime": 1393589200000,
                    "logStreamName": "my-log-stream-1",
                    "uploadSequenceToken": "88602967394531410094953670125156212707622379445839968487"
                },
                {
                    "storedBytes": 0,
                    "arn": "arn:aws:logs:us-east-1:123456789012:log-group:my-log-group-2:log-stream:my-log-stream-2",
                    "creationTime": 1396224000000,
                    "firstEventTimestamp": 1396224000000,
                    "lastEventTimestamp": 1396235500000,
                    "lastIngestionTime": 1396225560000,
                    "logStreamName": "my-log-stream-2",
                    "uploadSequenceToken": "07622379445839968487886029673945314100949536701251562127"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new DescribeLogStreamsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        /** @var LogStream[] $logStreams */
        $logStreams = iterator_to_array($result->getLogStreams(true));

        self::assertCount(2, $logStreams);
        self::assertSame('arn:aws:logs:us-east-1:123456789012:log-group:my-log-group-1:log-stream:my-log-stream-1', $logStreams[0]->getArn());
        self::assertSame('1393545600000', $logStreams[0]->getCreationTime());
        self::assertSame('1393545600000', $logStreams[0]->getFirstEventTimestamp());
        self::assertSame('1393567800000', $logStreams[0]->getLastEventTimestamp());
        self::assertSame('1393589200000', $logStreams[0]->getLastIngestionTime());
        self::assertSame('my-log-stream-1', $logStreams[0]->getLogStreamName());
        self::assertSame('88602967394531410094953670125156212707622379445839968487', $logStreams[0]->getUploadSequenceToken());
    }

    /**
     * @group legacy
     * @expectedDeprecation The property "storedBytes" of "%s" is deprecated by AWS.
     */
    public function testDescribeLogStreamsResponseDeprecatedAttribute(): void
    {
        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogStreams.html
        $response = new SimpleMockedResponse('{
            "logStreams": [
                {
                    "storedBytes": 0,
                    "arn": "arn:aws:logs:us-east-1:123456789012:log-group:my-log-group-1:log-stream:my-log-stream-1",
                    "creationTime": 1393545600000,
                    "firstEventTimestamp": 1393545600000,
                    "lastEventTimestamp": 1393567800000,
                    "lastIngestionTime": 1393589200000,
                    "logStreamName": "my-log-stream-1",
                    "uploadSequenceToken": "88602967394531410094953670125156212707622379445839968487"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new DescribeLogStreamsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        /** @var LogStream[] $logStreams */
        $logStreams = iterator_to_array($result->getLogStreams(true));

        self::assertSame('0', $logStreams[0]->getStoredBytes());
    }
}
