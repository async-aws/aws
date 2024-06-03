<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListTagsForStreamInput;

class ListTagsForStreamInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListTagsForStreamInput([
            'StreamName' => 'exampleStreamName',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListTagsForStream.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.ListTagsForStream
Accept: application/json

{
  "StreamName": "exampleStreamName"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
