<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Input\SignRequest;

class SignRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new SignRequest([
            'KeyId' => 'change me',
            'Message' => 'change me',
            'MessageType' => 'change me',
            'GrantTokens' => ['change me'],
            'SigningAlgorithm' => 'change me',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1

            {
            "KeyId": "alias\\/ECC_signing_key",
            "Message": "<message to be signed>",
            "MessageType": "RAW",
            "SigningAlgorithm": "ECDSA_SHA_384"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
