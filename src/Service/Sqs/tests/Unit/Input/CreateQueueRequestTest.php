<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\CreateQueueRequest;

class CreateQueueRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateQueueRequest([
            'QueueName' => 'MyQueue',
            'Attributes' => ['DelaySeconds' => '45'],
            'tags' => ['team' => 'Engineering'],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html */
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.CreateQueue

            {
                "QueueName":"MyQueue",
                "Attributes": {
                    "DelaySeconds": "45"
                },
                "tags": {
                    "team": "Engineering"
                }
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
