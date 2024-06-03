<?php

namespace AsyncAws\Kinesis\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Enum\EncryptionType;
use AsyncAws\Kinesis\Input\StopStreamEncryptionInput;

class StopStreamEncryptionInputTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StopStreamEncryptionInput([
            'StreamName' => 'exampleStreamName',
            'EncryptionType' => EncryptionType::KMS,
            'KeyId' => 'key1',
        ]);

        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_StopStreamEncryption.html
        $expected = '
POST / HTTP/1.0
Content-Type: application/x-amz-json-1.1
x-amz-target: Kinesis_20131202.StopStreamEncryption
Accept: application/json

{
    "StreamName": "exampleStreamName",
    "EncryptionType": "KMS",
    "KeyId": "key1"
}';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
