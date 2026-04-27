<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\ListMessageMoveTasksRequest;

class ListMessageMoveTasksRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListMessageMoveTasksRequest([
            'SourceArn' => 'arn:aws:sqs:us-east-1:555555555555:MyDeadLetterQueue',
            'MaxResults' => 5,
        ]);

        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ListMessageMoveTasks.html
        $expected = '
            POST / HTTP/1.0
            X-Amz-Target: AmazonSQS.ListMessageMoveTasks
            Content-Type: application/x-amz-json-1.0
            accept: application/json

            {
                "SourceArn": "arn:aws:sqs:us-east-1:555555555555:MyDeadLetterQueue",
                "MaxResults": 5
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
