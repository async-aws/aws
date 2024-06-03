<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\RemoveTagsFromStreamInput;

class RemoveTagsFromStreamInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new RemoveTagsFromStreamInput([
            'StreamName' => 'exampleStreamName',
            'TagKeys' => ['Project', 'Environment'],
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RemoveTagsFromStream.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.RemoveTagsFromStream
Accept: application/json

{
  "StreamName": "exampleStreamName",
  "TagKeys": ["Project", "Environment"]
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
