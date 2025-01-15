<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\DeleteSuppressedDestinationRequest;

class DeleteSuppressedDestinationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteSuppressedDestinationRequest([
            'EmailAddress' => 'test@example.org',
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_DeleteSuppressedDestination.html
        $expected = '
            DELETE /v2/email/suppression/addresses/test%40example.org HTTP/1.1
            Content-Type: application/json
            Accept: application/json
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
