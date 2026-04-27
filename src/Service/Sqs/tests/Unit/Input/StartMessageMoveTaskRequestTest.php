<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\StartMessageMoveTaskRequest;

class StartMessageMoveTaskRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartMessageMoveTaskRequest([
            'SourceArn' => 'arn:aws:sqs:us-east-1:555555555555:MyDeadLetterQueue',
            'DestinationArn' => 'arn:aws:sqs:us-east-1:555555555555:MyQueue',
            'MaxNumberOfMessagesPerSecond' => 5,
        ]);

        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_StartMessageMoveTask.html
        $expected = '
            POST / HTTP/1.0
            X-Amz-Target: AmazonSQS.StartMessageMoveTask
            Content-Type: application/x-amz-json-1.0
            accept: application/json

            {
                "SourceArn": "arn:aws:sqs:us-east-1:555555555555:MyDeadLetterQueue",
                "DestinationArn": "arn:aws:sqs:us-east-1:555555555555:MyQueue",
                "MaxNumberOfMessagesPerSecond": 5
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
