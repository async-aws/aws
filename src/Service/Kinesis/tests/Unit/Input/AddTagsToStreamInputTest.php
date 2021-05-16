<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\AddTagsToStreamInput;

class AddTagsToStreamInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new AddTagsToStreamInput([
            'StreamName' => 'change me',
            'Tags' => ['change me' => 'change me'],
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_AddTagsToStream.html
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
