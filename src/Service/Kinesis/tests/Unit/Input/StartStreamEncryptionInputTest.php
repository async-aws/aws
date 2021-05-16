<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\StartStreamEncryptionInput;

class StartStreamEncryptionInputTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new StartStreamEncryptionInput([
            'StreamName' => 'change me',
            'EncryptionType' => 'change me',
            'KeyId' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_StartStreamEncryption.html
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
