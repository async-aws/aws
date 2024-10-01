<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\AddPermissionRequest;

class AddPermissionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AddPermissionRequest([
            'QueueUrl' => 'https://sqs.us-east-1.amazonaws.com/177715257436/MyQueue/',
            'Label' => 'MyLabel',
            'AWSAccountIds' => ['177715257436', '111111111111'],
            'Actions' => ['SendMessage', 'ReceiveMessage'],
        ]);

        // see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_AddPermission.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.0
            x-amz-target: AmazonSQS.AddPermission
            Accept: application/json

            {
                "QueueUrl": "https://sqs.us-east-1.amazonaws.com/177715257436/MyQueue/",
                "Label": "MyLabel",
                "Actions": ["SendMessage", "ReceiveMessage"],
                "AWSAccountIds": ["177715257436", "111111111111"]
            }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
