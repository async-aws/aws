<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\EncryptionType;
use AsyncAws\Kinesis\Input\StartStreamEncryptionInput;

class StartStreamEncryptionInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartStreamEncryptionInput([
            'StreamName' => 'exampleStreamName',
            'EncryptionType' => EncryptionType::KMS,
            'KeyId' => 'key1',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_StartStreamEncryption.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.StartStreamEncryption

{
    "StreamName": "exampleStreamName",
    "EncryptionType": "KMS",
    "KeyId": "key1"
}
';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
