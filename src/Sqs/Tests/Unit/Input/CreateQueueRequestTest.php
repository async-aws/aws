<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\CreateQueueRequest;
use PHPUnit\Framework\TestCase;

class CreateQueueRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new CreateQueueRequest([
            'QueueName' => 'MyQueue',
            'Attributes' => ['DelaySeconds' => '45'],
            'tags' => ['team' => 'Engineering'],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_CreateQueue.html */
        $expected = trim('
Action=CreateQueue
&Version=2012-11-05
&QueueName=MyQueue
&Attribute.1.Name=DelaySeconds
&Attribute.1.Value=45
&Tag.1.Key=team
&Tag.1.Value=Engineering
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
