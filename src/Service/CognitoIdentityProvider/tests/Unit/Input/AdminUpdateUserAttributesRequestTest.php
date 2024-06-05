<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Test\TestCase;

class AdminUpdateUserAttributesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminUpdateUserAttributesRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
            'UserAttributes' => [new AttributeType([
                'Name' => 'phone_number',
                'Value' => '+33600000000',
            ])],
            'ClientMetadata' => ['redirect_url' => 'https://google.com'],
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminUpdateUserAttributes.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.AdminUpdateUserAttributes
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
                    "ClientMetadata": {
                        "redirect_url": "https://google.com"
                    }
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
