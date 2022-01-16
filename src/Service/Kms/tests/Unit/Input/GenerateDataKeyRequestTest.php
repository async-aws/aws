<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;

class GenerateDataKeyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GenerateDataKeyRequest([
            'EncryptionContext' => ['foo' => 'bar'],
            'GrantTokens' => ['foo', 'bar'],
            'KeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
            'KeySpec' => DataKeySpec::AES_256,
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-TARGET: TrentService.GenerateDataKey

            {
                "EncryptionContext": {"foo": "bar"},
                "GrantTokens": ["foo", "bar"],
                "KeyId": "1234abcd-12ab-34cd-56ef-1234567890ab",
                "KeySpec": "AES_256"
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
