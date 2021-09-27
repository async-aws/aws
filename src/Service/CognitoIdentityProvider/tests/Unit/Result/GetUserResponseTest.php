<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\GetUserResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetUserResponseTest extends TestCase
{
    public function testGetUserResponse(): void
    {
        // see https://docs.aws.amazon.com/cognitoidentityprovider/latest/APIReference/API_GetUser.html
        $response = new SimpleMockedResponse('{
            "MFAOptions": [
                {
                    "AttributeName": "phone_number",
                    "DeliveryMedium": "EMAIL"
                }
            ],
            "PreferredMfaSetting": "phone_number",
            "UserAttributes": [
                {
                    "Name": "phone_number",
                    "Value": "+33600000000"
                }
            ],
            "UserMFASettingList": [ "string" ],
            "Username": "1c202820-8eb5-11ea-bc55-0242ac130003"
        }');

        $client = new MockHttpClient($response);
        $result = new GetUserResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('1c202820-8eb5-11ea-bc55-0242ac130003', $result->getUsername());
        self::assertIsArray($result->getUserAttributes());
        self::assertIsArray($result->getMFAOptions());
        self::assertSame('phone_number', $result->getPreferredMfaSetting());
        self::assertIsArray($result->getUserMFASettingList());
    }
}
