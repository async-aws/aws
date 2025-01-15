<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\GetSuppressedDestinationRequest;

class GetSuppressedDestinationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetSuppressedDestinationRequest([
            'EmailAddress' => 'test@example.org',
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetSuppressedDestination.html
        $expected = '
            GET /v2/email/suppression/addresses/test%40example.org HTTP/1.1
            Content-Type: application/json
            Accept: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
