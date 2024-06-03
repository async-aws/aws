<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListStreamsInput;

class ListStreamsInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListStreamsInput([
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreams.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.ListStreams
Accept: application/json

{}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
