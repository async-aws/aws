<?php

namespace AsyncAws\Athena\Tests\Unit\Input;

use AsyncAws\Athena\Input\GetSessionStatusRequest;
use AsyncAws\Core\Test\TestCase;

class GetSessionStatusRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetSessionStatusRequest([
            'SessionId' => 'my_session_1236',
        ]);

        // see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetSessionStatus.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AmazonAthena.GetSessionStatus

            {
            "SessionId": "my_session_1236"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
