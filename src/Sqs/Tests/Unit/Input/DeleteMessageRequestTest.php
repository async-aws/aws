<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\DeleteMessageRequest;
use PHPUnit\Framework\TestCase;

class DeleteMessageRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new DeleteMessageRequest([
            'QueueUrl' => 'queueUrl',
            'ReceiptHandle' => 'MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljT',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_DeleteMessage.html */
        $expected = trim('
Action=DeleteMessage
&Version=2012-11-05
&QueueUrl=queueUrl
&ReceiptHandle=MbZj6wDWli%2BJvwwJaBV%2B3dcjk2YW2vA3%2BSTFFljT
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
