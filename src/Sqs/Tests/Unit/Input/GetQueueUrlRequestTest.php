<?php

namespace AsyncAws\Sqs\Tests\Unit\Input;

use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use PHPUnit\Framework\TestCase;

class GetQueueUrlRequestTest extends TestCase
{
    public function testRequestBody(): void
    {
        self::markTestIncomplete('Not implemented');

        $input = new GetQueueUrlRequest([
            'QueueName' => 'change me',
            'QueueOwnerAWSAccountId' => 'change me',
        ]);

        $expected = trim('
        Action=GetQueueUrl
        &Version=2012-11-05
        &ChangeIt=Change+it
                        ');

        self::assertEquals($expected, \str_replace('&', "\n&", $input->requestBody()));
    }
}
