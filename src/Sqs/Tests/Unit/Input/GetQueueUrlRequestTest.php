<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use PHPUnit\Framework\TestCase;

class GetQueueUrlRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new GetQueueUrlRequest([
            'QueueName' => 'MyQueue',
            'QueueOwnerAWSAccountId' => '123456',
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueUrl.html */
        $expected = trim('
Action=GetQueueUrl
&Version=2012-11-05
&QueueName=MyQueue
&QueueOwnerAWSAccountId=123456
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
