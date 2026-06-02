<?php

namespace AsyncAws\SsoOidc\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Result\StartDeviceAuthorizationResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class StartDeviceAuthorizationResponseTest extends TestCase
{
    public function testStartDeviceAuthorizationResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "deviceCode": "yJraWQiOiJrZXktMTU2Njk2ODA4OCIsImFsZyI6IkhTMzIn0EXAMPLEDEVICECODE",
            "expiresIn": 1579729529,
            "interval": 1,
            "userCode": "makdfsk83yJraWQiOiJrZXktMTU2Njk2sImFsZyI6IkhTMzIn0EXAMPLEUSERCODE",
            "verificationUri": "https:\\/\\/directory-alias-example.awsapps.com\\/start\\/#\\/device",
            "verificationUriComplete": "https:\\/\\/directory-alias-example.awsapps.com\\/start\\/#\\/device?user_code=makdfsk83yJraWQiOiJrZXktMTU2Njk2sImFsZyI6IkhTMzIn0EXAMPLEUSERCODE"
        }');

        $client = new MockHttpClient($response);
        $result = new StartDeviceAuthorizationResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('yJraWQiOiJrZXktMTU2Njk2ODA4OCIsImFsZyI6IkhTMzIn0EXAMPLEDEVICECODE', $result->getDeviceCode());
        self::assertSame('makdfsk83yJraWQiOiJrZXktMTU2Njk2sImFsZyI6IkhTMzIn0EXAMPLEUSERCODE', $result->getUserCode());
        self::assertSame('https://directory-alias-example.awsapps.com/start/#/device', $result->getVerificationUri());
        self::assertSame('https://directory-alias-example.awsapps.com/start/#/device?user_code=makdfsk83yJraWQiOiJrZXktMTU2Njk2sImFsZyI6IkhTMzIn0EXAMPLEUSERCODE', $result->getVerificationUriComplete());
        self::assertSame(1579729529, $result->getExpiresIn());
        self::assertSame(1, $result->getInterval());
    }
}
