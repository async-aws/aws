<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Input\EncryptRequest;

class EncryptRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new EncryptRequest([
            'EncryptionAlgorithm' => EncryptionAlgorithmSpec::SYMMETRIC_DEFAULT,
            'EncryptionContext' => ['foo' => 'bar'],
            'GrantTokens' => ['foo', 'bar'],
            'KeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
            'Plaintext' => '{"message": "Hello, World!"}',
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-TARGET: TrentService.Encrypt

            {
                "EncryptionAlgorithm": "SYMMETRIC_DEFAULT",
                "EncryptionContext": {"foo": "bar"},
                "GrantTokens": ["foo", "bar"],
                "KeyId": "1234abcd-12ab-34cd-56ef-1234567890ab",
                "Plaintext": "eyJtZXNzYWdlIjogIkhlbGxvLCBXb3JsZCEifQ=="
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
