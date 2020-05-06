<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Result;

use AsyncAws\CognitoIdentityProvider\Result\ListUsersResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListUsersResponseTest extends TestCase
{
    public function testListUsersResponse(): void
    {
        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ListUsers.html
        $response = new SimpleMockedResponse('{
            "PaginationToken": "56BBCBD93B1D456F",
            "Users": [
                {
                    "Attributes": [
                        {
                        "Name": "phone_number",
                        "Value": "+33600000000"
                        }
                    ],
                    "Enabled": false,
                    "MFAOptions": [
                        {
                        "AttributeName": "phone_number",
                        "DeliveryMedium": "SMS"
                        }
                    ],
                    "UserCreateDate": 1588676164,
                    "UserLastModifiedDate": 1588676164,
                    "Username": "1c202820-8eb5-11ea-bc55-0242ac130003",
                    "UserStatus": "CONFIRMED"
                }
            ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListUsersResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertIsIterable($result->getUsers());
        self::assertSame('56BBCBD93B1D456F', $result->getPaginationToken());
    }
}
