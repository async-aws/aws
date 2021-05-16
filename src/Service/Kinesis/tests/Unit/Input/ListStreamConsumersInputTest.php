<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListStreamConsumersInput;

class ListStreamConsumersInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new ListStreamConsumersInput([
            'StreamARN' => 'change me',
            'NextToken' => 'change me',
            'MaxResults' => 1337,
            'StreamCreationTimestamp' => new \DateTimeImmutable(),
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreamConsumers.html
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
