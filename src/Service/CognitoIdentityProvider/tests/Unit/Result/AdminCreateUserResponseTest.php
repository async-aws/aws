<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Enum\UserStatusType;
use AsyncAws\CognitoIdentityProvider\Result\AdminCreateUserResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserType;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class AdminCreateUserResponseTest extends TestCase
{
    public function testAdminCreateUserResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminCreateUser.html
        $response = new SimpleMockedResponse('{
            "User": {
                "Attributes": [
                    {
                        "Name": "phone_number",
                        "Value": "+33600000000"
                    }
                ],
                "Enabled": true,
                "MFAOptions": [
                    {
                        "AttributeName": "phone_number",
                        "DeliveryMedium": "EMAIL"
                    }
                ],
                "Username": "1c202820-8eb5-11ea-bc55-0242ac130003",
                "UserStatus": "CONFIRMED"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new AdminCreateUserResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $user = $result->getUser();
        self::assertInstanceOf(UserType::class, $user);
        self::assertSame('1c202820-8eb5-11ea-bc55-0242ac130003', $user->getUsername());
        self::assertSame(UserStatusType::CONFIRMED, $user->getUserStatus());
        self::assertIsArray($user->getMFAOptions());
    }
}
