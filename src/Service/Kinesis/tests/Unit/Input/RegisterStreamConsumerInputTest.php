<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\RegisterStreamConsumerInput;

class RegisterStreamConsumerInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new RegisterStreamConsumerInput([
            'StreamARN' => 'change me',
            'ConsumerName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RegisterStreamConsumer.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
