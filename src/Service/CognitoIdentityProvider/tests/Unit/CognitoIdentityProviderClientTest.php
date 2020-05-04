<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\Result\AdminUpdateUserAttributesResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CognitoIdentityProviderClientTest extends TestCase
{
    public function testAdminUpdateUserAttributes(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminUpdateUserAttributesRequest([
            'UserPoolId' => 'us-east-1_1337oL33t',
            'Username' => '1c202820-8eb5-11ea-bc55-0242ac130003',
            'UserAttributes' => [new AttributeType([
                'Name' => 'phone_number',
                'Value' => '+33600000000',
            ])],

        ]);
        $result = $client->AdminUpdateUserAttributes($input);

        self::assertInstanceOf(AdminUpdateUserAttributesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
