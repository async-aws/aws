<?php

namespace AsyncAws\SsoOidc\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;

class CreateTokenRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateTokenRequest([
            'clientId' => '_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID',
            'clientSecret' => 'VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0',
            'grantType' => 'urn:ietf:params:oauth:grant-type:device-code',
            'deviceCode' => 'yJraWQiOiJrZXktMTU2Njk2ODA4OCIsImFsZyI6IkhTMzIn0EXAMPLEDEVICECODE',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST /token HTTP/1.0
            Content-Type: application/json
            Accept: application/json

            {
                "clientId": "_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID",
                "clientSecret": "VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0",
                "deviceCode": "yJraWQiOiJrZXktMTU2Njk2ODA4OCIsImFsZyI6IkhTMzIn0EXAMPLEDEVICECODE",
                "grantType": "urn:ietf:params:oauth:grant-type:device-code"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
