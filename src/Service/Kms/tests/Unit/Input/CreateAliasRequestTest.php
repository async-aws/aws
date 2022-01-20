<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Input\CreateAliasRequest;

class CreateAliasRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateAliasRequest([
            'AliasName' => 'alias/ExampleAlias',
            'TargetKeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: TrentService.CreateAlias

            {
                "AliasName": "alias\\/ExampleAlias",
                "TargetKeyId": "1234abcd-12ab-34cd-56ef-1234567890ab"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
