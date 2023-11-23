<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Sqs\Result\GetQueueAttributesResult;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetQueueAttributesResultTest extends TestCase
{
    public function testGetQueueAttributesResult()
    {
        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueAttributes.html
        $response = new SimpleMockedResponse(<<<JSON
{
    "Attributes": {
       "QueueArn": "arn:aws:sqs:us-east-1:555555555555:MyQueue",
        "ApproximateNumberOfMessages": "0",
        "ApproximateNumberOfMessagesNotVisible": "0",
        "ApproximateNumberOfMessagesDelayed": "0",
        "CreatedTimestamp": "1676665337",
        "LastModifiedTimestamp": "1677096375",
        "VisibilityTimeout": "60",
        "MaximumMessageSize": "8192",
        "MessageRetentionPeriod": "345600",
        "DelaySeconds": "0",
        "Policy": "{\"Version\":\"2012-10-17\",\"Id\":\"Policy1677095510157\",\"Statement\":[{\"Sid\":\"Stmt1677095506939\",\"Effect\":\"Allow\",\"Principal\":\"*\",\"Action\":\"sqs:ReceiveMessage\",\"Resource\":\"arn:aws:sqs:us-east-1:555555555555:MyQueue6\"}]}",
        "RedriveAllowPolicy": "{\"redrivePermission\":\"allowAll\"}",
        "ReceiveMessageWaitTimeSeconds": "2",
        "SqsManagedSseEnabled": "true"
    }
}
JSON
        );

        $client = new MockHttpClient($response);
        $result = new GetQueueAttributesResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertArrayHasKey('MaximumMessageSize', $result->getAttributes());
        self::assertEquals('8192', $result->getAttributes()['MaximumMessageSize']);
    }
}
