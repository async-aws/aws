<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Input\GetPublicKeyRequest;

class GetPublicKeyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetPublicKeyRequest([
            'KeyId' => 'arn:aws:kms:us-west-2:111122223333:key/0987dcba-09fe-87dc-65ba-ab0987654321',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-TARGET: TrentService.GetPublicKey
            Accept: application/json

            {
            "KeyId": "arn:aws:kms:us-west-2:111122223333:key\\/0987dcba-09fe-87dc-65ba-ab0987654321"
        }';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
