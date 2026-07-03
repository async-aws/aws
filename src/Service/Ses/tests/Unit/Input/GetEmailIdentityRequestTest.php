<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\GetEmailIdentityRequest;

class GetEmailIdentityRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new GetEmailIdentityRequest([
            'EmailIdentity' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetEmailIdentity.html
        $expected = '
            GET / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
