<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\MessageType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;
use AsyncAws\Kms\Input\SignRequest;

class SignRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SignRequest([
            'KeyId' => 'alias\\/ECC_signing_key',
            'Message' => '<message to be signed>',
            'MessageType' => MessageType::RAW,
            'SigningAlgorithm' => SigningAlgorithmSpec::RSASSA_PSS_SHA_512,
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "KeyId": "alias\\/ECC_signing_key",
            "Message": "<message to be signed>",
            "MessageType": "RAW",
            "SigningAlgorithm": "RSASSA_PSS_SHA_512"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
