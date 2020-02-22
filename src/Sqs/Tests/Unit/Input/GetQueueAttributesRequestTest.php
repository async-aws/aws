<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\GetQueueAttributesRequest;
use PHPUnit\Framework\TestCase;

class GetQueueAttributesRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new GetQueueAttributesRequest([
            'QueueUrl' => 'change me',
            'AttributeNames' => ['change me'],
        ]);

        $expected = trim('
        Action=GetQueueAttributes
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
