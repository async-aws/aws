<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\ChangeMessageVisibilityRequest;
use PHPUnit\Framework\TestCase;

class ChangeMessageVisibilityRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new ChangeMessageVisibilityRequest([
            'QueueUrl' => 'change me',
            'ReceiptHandle' => 'change me',
            'VisibilityTimeout' => 1337,
        ]);

        $expected = trim('
        Action=ChangeMessageVisibility
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
