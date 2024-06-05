<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetSessionRequest;
use AsyncAws\Core\Test\TestCase;

class GetSessionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetSessionRequest([
            'SessionId' => 'iad-aws-Athena23669',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetSession.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetSession
            Accept: application/json

            {
            "SessionId": "iad-aws-Athena23669"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
