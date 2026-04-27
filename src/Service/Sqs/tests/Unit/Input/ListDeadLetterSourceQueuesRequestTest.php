<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\ListDeadLetterSourceQueuesRequest;

class ListDeadLetterSourceQueuesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListDeadLetterSourceQueuesRequest([
            'QueueUrl' => 'https://sqs.us-east-1.amazonaws.com/177715257436/MyQueue',
            'MaxResults' => 5,
        ]);

        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListDeadLetterSourceQueues.html
        $expected = '
            POST / HTTP/1.1
            X-Amz-Target: AmazonSQS.ListDeadLetterSourceQueues
            Content-Type: application/x-amz-json-1.0
            accept: application/json

            {
                "QueueUrl": "https://sqs.us-east-1.amazonaws.com/177715257436/MyQueue",
                "MaxResults": 5
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
