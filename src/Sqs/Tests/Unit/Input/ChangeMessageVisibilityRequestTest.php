<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\ChangeMessageVisibilityRequest;
use PHPUnit\Framework\TestCase;

class ChangeMessageVisibilityRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new ChangeMessageVisibilityRequest([
            'QueueUrl' => 'queueUrl',
            'ReceiptHandle' => 'MbZj6wDWli+JvwwJaBV+3dcjk2YW2vA3+STFFljT',
            'VisibilityTimeout' => 60,
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_ChangeMessageVisibility.html */
        $expected = trim('
Action=ChangeMessageVisibility
&Version=2012-11-05
&QueueUrl=queueUrl
&ReceiptHandle=MbZj6wDWli%2BJvwwJaBV%2B3dcjk2YW2vA3%2BSTFFljT
&VisibilityTimeout=60
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
