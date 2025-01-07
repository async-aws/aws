<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\MessageType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;
use AsyncAws\Kms\Input\VerifyRequest;

class VerifyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new VerifyRequest([
            'KeyId' => 'alias/ECC_signing_key',
            'Message' => "<message to be verified>",
            'MessageType' => MessageType::RAW,            
            'SigningAlgorithm' => SigningAlgorithmSpec::ECDSA_SHA_384,
            'Signature' => '<binary data>',
            'DryRun' => false,
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: TrentService.Verify
            Accept: application/json

            {
            "DryRun": false,
            "KeyId": "alias\\/ECC_signing_key",
            "Message": "PG1lc3NhZ2UgdG8gYmUgdmVyaWZpZWQ+",
            "MessageType": "RAW",
            "Signature": "PGJpbmFyeSBkYXRhPg==",
            "SigningAlgorithm": "ECDSA_SHA_384"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
