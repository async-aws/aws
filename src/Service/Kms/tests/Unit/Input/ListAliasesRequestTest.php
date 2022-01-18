<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Input\ListAliasesRequest;

class ListAliasesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new ListAliasesRequest([
            'KeyId' => '1234abcd-12ab-34cd-56ef-1234567890ab',
            'Limit' => 10,
        ]);

        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            X-AMZ-TARGET: TrentService.ListAliases

            {
                "KeyId": "1234abcd-12ab-34cd-56ef-1234567890ab",
                "Limit": 10
            }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
