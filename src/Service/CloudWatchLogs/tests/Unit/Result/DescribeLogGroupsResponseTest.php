<?php

namespace AsyncAws\CloudWatchLogs\Tests\Unit\Result;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\DescribeLogGroupsRequest;
use AsyncAws\CloudWatchLogs\Result\DescribeLogGroupsResponse;
use AsyncAws\CloudWatchLogs\ValueObject\LogGroup;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeLogGroupsResponseTest extends TestCase
{
    public function testDescribeLogGroupsResponse(): void
    {
        // see https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_DescribeLogGroups.html
        $response = new SimpleMockedResponse('{
          "logGroups": [
            {
              "storageBytes": 1048576,
              "arn": "arn:aws:logs:us-east-1:123456789012:log-group:my-log-group-1:*",
              "creationTime": 1393545600000,
              "logGroupName": "my-log-group-1",
              "metricFilterCount": 0,
              "retentionInDays": 14,
              "kmsKeyId": "arn:aws:kms:us-east-1:123456789012:key/abcd1234-a123-456a-a12b-a123b4cd56ef"
            },
            {
              "storageBytes": 5242880,
              "arn": "arn:aws:logs:us-east-1:123456789012:log-group:my-log-group-2:*",
              "creationTime": 1396224000000,
              "logGroupName": "my-log-group-2",
              "metricFilterCount": 0,
              "retentionInDays": 30
            }
          ]
        }');

        $client = new MockHttpClient($response);
        $result = new DescribeLogGroupsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new CloudWatchLogsClient(), new DescribeLogGroupsRequest([]));

        /** @var LogGroup[] $logGroups */
        $logGroups = iterator_to_array($result->getLogGroups(true));

        self::assertCount(2, $logGroups);
        self::assertSame('arn:aws:logs:us-east-1:123456789012:log-group:my-log-group-1:*', $logGroups[0]->getArn());
        self::assertSame(1393545600000, $logGroups[0]->getCreationTime());
        self::assertSame('my-log-group-1', $logGroups[0]->getLogGroupName());
        self::assertSame(0, $logGroups[0]->getMetricFilterCount());
        self::assertSame(14, $logGroups[0]->getRetentionInDays());
        self::assertSame('arn:aws:kms:us-east-1:123456789012:key/abcd1234-a123-456a-a12b-a123b4cd56ef', $logGroups[0]->getKmsKeyId());
    }
}
