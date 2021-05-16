<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\DeleteStreamInput;

class DeleteStreamInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new DeleteStreamInput([
            'StreamName' => 'change me',
            'EnforceConsumerDeletion' => false,
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DeleteStream.html
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
