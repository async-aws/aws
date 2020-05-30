<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Integration;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Input\AdminCreateUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminDeleteUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminGetUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminInitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\Input\AssociateSoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Input\ChangePasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\CognitoIdentityProvider\Input\SetUserMFAPreferenceRequest;
use AsyncAws\CognitoIdentityProvider\Input\VerifySoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\ContextDataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\HttpHeader;
use AsyncAws\CognitoIdentityProvider\ValueObject\SMSMfaSettingsType;
use AsyncAws\CognitoIdentityProvider\ValueObject\SoftwareTokenMfaSettingsType;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CognitoIdentityProviderClientTest extends TestCase
{
    public function testAdminCreateUser(): void
    {
        $client = $this->getClient();

        $input = new AdminCreateUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
            'UserAttributes' => [new AttributeType([
                'Name' => 'change me',
                'Value' => 'change me',
            ])],
            'ValidationData' => [new AttributeType([
                'Name' => 'change me',
                'Value' => 'change me',
            ])],
            'TemporaryPassword' => 'change me',
            'ForceAliasCreation' => false,
            'MessageAction' => 'change me',
            'DesiredDeliveryMediums' => ['change me'],
            'ClientMetadata' => ['change me' => 'change me'],
        ]);
        $result = $client->AdminCreateUser($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getUser());
    }

    public function testAdminDeleteUser(): void
    {
        $client = $this->getClient();

        $input = new AdminDeleteUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->AdminDeleteUser($input);

        $result->resolve();
    }

    public function testAdminGetUser(): void
    {
        $client = $this->getClient();

        $input = new AdminGetUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->AdminGetUser($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getUsername());
        // self::assertTODO(expected, $result->getUserAttributes());
        // self::assertTODO(expected, $result->getUserCreateDate());
        // self::assertTODO(expected, $result->getUserLastModifiedDate());
        self::assertFalse($result->getEnabled());
        self::assertSame('changeIt', $result->getUserStatus());
        // self::assertTODO(expected, $result->getMFAOptions());
        self::assertSame('changeIt', $result->getPreferredMfaSetting());
        // self::assertTODO(expected, $result->getUserMFASettingList());
    }

    public function testAdminInitiateAuth(): void
    {
        $client = $this->getClient();

        $input = new AdminInitiateAuthRequest([
            'UserPoolId' => 'change me',
            'ClientId' => 'change me',
            'AuthFlow' => 'change me',
            'AuthParameters' => ['change me' => 'change me'],
            'ClientMetadata' => ['change me' => 'change me'],
            'AnalyticsMetadata' => new AnalyticsMetadataType([
                'AnalyticsEndpointId' => 'change me',
            ]),
            'ContextData' => new ContextDataType([
                'IpAddress' => 'change me',
                'ServerName' => 'change me',
                'ServerPath' => 'change me',
                'HttpHeaders' => [new HttpHeader([
                    'headerName' => 'change me',
                    'headerValue' => 'change me',
                ])],
                'EncodedData' => 'change me',
            ]),
        ]);
        $result = $client->AdminInitiateAuth($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getChallengeName());
        self::assertSame('changeIt', $result->getSession());
        // self::assertTODO(expected, $result->getChallengeParameters());
        // self::assertTODO(expected, $result->getAuthenticationResult());
    }

    public function testAdminUpdateUserAttributes(): void
    {
        $client = $this->getClient();

        $input = new AdminUpdateUserAttributesRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
            'UserAttributes' => [new AttributeType([
                'Name' => 'change me',
                'Value' => 'change me',
            ])],
            'ClientMetadata' => ['change me' => 'change me'],
        ]);
        $result = $client->AdminUpdateUserAttributes($input);

        $result->resolve();
    }

    public function testAssociateSoftwareToken(): void
    {
        $client = $this->getClient();

        $input = new AssociateSoftwareTokenRequest([
            'AccessToken' => 'change me',
            'Session' => 'change me',
        ]);
        $result = $client->AssociateSoftwareToken($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getSecretCode());
        self::assertSame('changeIt', $result->getSession());
    }

    public function testChangePassword(): void
    {
        $client = $this->getClient();

        $input = new ChangePasswordRequest([
            'PreviousPassword' => 'change me',
            'ProposedPassword' => 'change me',
            'AccessToken' => 'change me',
        ]);
        $result = $client->ChangePassword($input);

        $result->resolve();
    }

    public function testListUsers(): void
    {
        $client = $this->getClient();

        $input = new ListUsersRequest([
            'UserPoolId' => 'change me',
            'AttributesToGet' => ['change me'],
            'Limit' => 1337,
            'PaginationToken' => 'change me',
            'Filter' => 'change me',
        ]);
        $result = $client->ListUsers($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getUsers());
        self::assertSame('changeIt', $result->getPaginationToken());
    }

    public function testSetUserMFAPreference(): void
    {
        $client = $this->getClient();

        $input = new SetUserMFAPreferenceRequest([
            'SMSMfaSettings' => new SMSMfaSettingsType([
                'Enabled' => false,
                'PreferredMfa' => false,
            ]),
            'SoftwareTokenMfaSettings' => new SoftwareTokenMfaSettingsType([
                'Enabled' => false,
                'PreferredMfa' => false,
            ]),
            'AccessToken' => 'change me',
        ]);
        $result = $client->SetUserMFAPreference($input);

        $result->resolve();
    }

    public function testVerifySoftwareToken(): void
    {
        $client = $this->getClient();

        $input = new VerifySoftwareTokenRequest([
            'AccessToken' => 'change me',
            'Session' => 'change me',
            'UserCode' => 'change me',
            'FriendlyDeviceName' => 'change me',
        ]);
        $result = $client->VerifySoftwareToken($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getStatus());
        self::assertSame('changeIt', $result->getSession());
    }

    private function getClient(): CognitoIdentityProviderClient
    {
        self::markTestSkipped('No Docker image for Cognito Identity Provider');

        return new CognitoIdentityProviderClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
