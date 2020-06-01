<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Enum\AuthFlowType;
use AsyncAws\CognitoIdentityProvider\Input\AdminInitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\ContextDataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\HttpHeader;
use AsyncAws\Core\Test\TestCase;

class AdminInitiateAuthRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new AdminInitiateAuthRequest([
            'UserPoolId' => 'my pool',
            'ClientId' => 'clientI',
            'AuthFlow' => AuthFlowType::CUSTOM_AUTH,
            'AuthParameters' => ['key' => 'value'],
            'ClientMetadata' => ['key' => 'value'],
            'AnalyticsMetadata' => new AnalyticsMetadataType([
                'AnalyticsEndpointId' => 'my endpoint',
            ]),
            'ContextData' => new ContextDataType([
                'IpAddress' => '10.0.0.1',
                'ServerName' => 'mycompany.com',
                'ServerPath' => '/path',
                'HttpHeaders' => [new HttpHeader([
                    'headerName' => 'X-Header',
                    'headerValue' => 'headerValue',
                ])],
                'EncodedData' => 'data',
            ]),
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminInitiateAuth.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: AWSCognitoIdentityProviderService.AdminInitiateAuth

            {
                "UserPoolId": "my pool",
                "ClientId": "clientI",
                "AuthFlow": "CUSTOM_AUTH",
                "AuthParameters": {
                    "key": "value"
                },
                "ClientMetadata": {
                    "key": "value"
                },
                "AnalyticsMetadata": {
                    "AnalyticsEndpointId": "my endpoint"
                },
                "ContextData": {
                    "IpAddress": "10.0.0.1",
                    "ServerName": "mycompany.com",
                    "ServerPath": "/path",
                    "HttpHeaders": [
                        {
                            "headerName": "X-Header",
                            "headerValue": "headerValue"
                        }
                    ],
                    "EncodedData": "data"
                }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
