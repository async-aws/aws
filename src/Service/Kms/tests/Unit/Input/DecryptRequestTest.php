<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Input\DecryptRequest;

class DecryptRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DecryptRequest([
            'CiphertextBlob' => 'binary-ciphertext-string',
            'EncryptionAlgorithm' => EncryptionAlgorithmSpec::SYMMETRIC_DEFAULT,
            'EncryptionContext' => ['foo' => 'bar'],
            'GrantTokens' => ['foo', 'bar'],
            'KeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-TARGET: TrentService.Decrypt

            {
                "CiphertextBlob": "YmluYXJ5LWNpcGhlcnRleHQtc3RyaW5n",
                "EncryptionAlgorithm": "SYMMETRIC_DEFAULT",
                "EncryptionContext": {"foo": "bar"},
                "GrantTokens": ["foo", "bar"],
                "KeyId": "1234abcd-12ab-34cd-56ef-1234567890ab"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
