<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\AddTagsToStreamInput;

class AddTagsToStreamInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AddTagsToStreamInput([
            'StreamName' => 'exampleStreamName',
            'Tags' => ['Project' => 'myProject', 'Environment' => 'Production'],
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_AddTagsToStream.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.AddTagsToStream
Accept: application/json

{
  "StreamName": "exampleStreamName",
  "Tags": {
     "Project" : "myProject",
     "Environment" : "Production"
   }
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
