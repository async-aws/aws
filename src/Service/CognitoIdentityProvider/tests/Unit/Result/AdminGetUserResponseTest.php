<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Enum\UserStatusType;
use AsyncAws\CognitoIdentityProvider\Result\AdminGetUserResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminGetUserResponseTest extends TestCase
{
    public function testAdminGetUserResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminGetUser.html
        $response = new SimpleMockedResponse('{
            "Enabled": false,
            "MFAOptions": [
                {
                    "AttributeName": "phone_number",
                    "DeliveryMedium": "SMS"
                }
            ],
            "PreferredMfaSetting": "phone_number",
            "UserAttributes": [
                {
                    "Name": "phone_number",
                    "Value": "+33600000000"
                }
            ],
            "UserCreateDate": 1588676164,
            "UserLastModifiedDate": 1588676164,
            "Username": "1c202820-8eb5-11ea-bc55-0242ac130003",
            "UserStatus": "CONFIRMED"
        }');

        $client = new MockHttpClient($response);
        $result = new AdminGetUserResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('1c202820-8eb5-11ea-bc55-0242ac130003', $result->getUsername());
        self::assertIsArray($result->getUserAttributes());
        self::assertSame(1588676164, $result->getUserCreateDate()->getTimestamp());
        self::assertSame(1588676164, $result->getUserLastModifiedDate()->getTimestamp());
        self::assertFalse($result->getEnabled());
        self::assertSame(UserStatusType::CONFIRMED, $result->getUserStatus());
        self::assertIsArray($result->getMFAOptions());
        self::assertSame('phone_number', $result->getPreferredMfaSetting());
        self::assertIsArray($result->getUserMFASettingList());
    }
}
