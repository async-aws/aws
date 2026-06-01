<?php

namespace AsyncAws\SsoOidc\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\StartDeviceAuthorizationRequest;

class StartDeviceAuthorizationRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new StartDeviceAuthorizationRequest([
            'clientId' => '_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID',
            'clientSecret' => 'VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0',
            'startUrl' => 'https://identitycenter.amazonaws.com/ssoins-111111111111',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST /device_authorization HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {
            "clientId": "_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID",
            "clientSecret": "VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0",
            "startUrl": "https:\\/\\/identitycenter.amazonaws.com\\/ssoins-111111111111"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
