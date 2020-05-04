<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Integration;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class CognitoIdentityProviderClientTest extends TestCase
{
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

    private function getClient(): CognitoIdentityProviderClient
    {
        self::markTestSkipped('No Docker image for Cognito Identity Provider');

        return new CognitoIdentityProviderClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
