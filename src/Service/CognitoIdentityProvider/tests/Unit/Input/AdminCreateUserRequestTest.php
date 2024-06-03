<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminCreateUserRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Test\TestCase;

class AdminCreateUserRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminCreateUserRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
            'UserAttributes' => [new AttributeType([
                'Name' => 'phone_number',
                'Value' => '+33600000000',
            ])],
            'ValidationData' => [new AttributeType([
                'Name' => 'domain',
                'Value' => 'google.com',
            ])],
            'TemporaryPassword' => '$T3mpP4s5W0rd!',
            'ForceAliasCreation' => false,
            'MessageAction' => 'RESEND',
            'DesiredDeliveryMediums' => ['SMS', 'EMAIL'],
            'ClientMetadata' => ['redirect_url' => 'https://google.com'],
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminCreateUser.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.AdminCreateUser
                Accept: application/json

                {
                    "UserPoolId": "us-east-1_1337oL33t",
                    "Username": "1c202820-8eb5-11ea-bc55-0242ac130003",
                    "UserAttributes": [
                        {
                            "Name": "phone_number",
                            "Value": "+33600000000"
                        }
                    ],
                    "ValidationData": [
                        {
                            "Name": "domain",
                            "Value": "google.com"
                        }
                    ],
                    "TemporaryPassword": "$T3mpP4s5W0rd!",
                    "ForceAliasCreation": false,
                    "MessageAction": "RESEND",
                    "DesiredDeliveryMediums": [
                        "SMS",
                        "EMAIL"
                    ],
                    "ClientMetadata": {
                        "redirect_url": "https://google.com"
                    }
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
