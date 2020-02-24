<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sqs\Input\GetQueueAttributesRequest;

class GetQueueAttributesRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        $input = new GetQueueAttributesRequest([
            'QueueUrl' => 'queueUrl',
            'AttributeNames' => ['VisibilityTimeout', 'DelaySeconds', 'ReceiveMessageWaitTimeSeconds'],
        ]);

        /** @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_GetQueueAttributes.html */
        $expected = '
Action=GetQueueAttributes
&Version=2012-11-05
&QueueUrl=queueUrl
&AttributeName.1=VisibilityTimeout
&AttributeName.2=DelaySeconds
&AttributeName.3=ReceiveMessageWaitTimeSeconds
        ';

        self::assertHttpFormEqualsHttpForm($expected, $input->requestBody());
    }
}
