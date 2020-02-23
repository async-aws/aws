<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\GetQueueAttributesRequest;
use PHPUnit\Framework\TestCase;

class GetQueueAttributesRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new GetQueueAttributesRequest([
            'QueueUrl' => 'queueUrl',
            'AttributeNames' => ['VisibilityTimeout', 'DelaySeconds', 'ReceiveMessageWaitTimeSeconds'],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueAttributes.html */
        $expected = trim('
Action=GetQueueAttributes
&Version=2012-11-05
&QueueUrl=queueUrl
&AttributeName.1=VisibilityTimeout
&AttributeName.2=DelaySeconds
&AttributeName.3=ReceiveMessageWaitTimeSeconds
        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
