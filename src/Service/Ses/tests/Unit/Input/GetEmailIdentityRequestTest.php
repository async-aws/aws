<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\GetEmailIdentityRequest;

class GetEmailIdentityRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetEmailIdentityRequest([
            'EmailIdentity' => 'example.com',
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetEmailIdentity.html
        $expected = '
            GET /v2/email/identities/example.com HTTP/1.1
            Content-Type: application/json
            Accept: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
