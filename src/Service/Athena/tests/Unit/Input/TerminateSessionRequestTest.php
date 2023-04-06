<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\TerminateSessionRequest;
use AsyncAws\Core\Test\TestCase;

class TerminateSessionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new TerminateSessionRequest([
            'SessionId' => 'my-iad-session',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_TerminateSession.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.TerminateSession

            {
            "SessionId": "my-iad-session"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
