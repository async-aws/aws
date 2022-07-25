<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit;

use AsyncAws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use AsyncAws\CognitoIdentityProvider\Enum\AuthFlowType;
use AsyncAws\CognitoIdentityProvider\Input\AdminConfirmSignUpRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminCreateUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminDeleteUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminDisableUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminEnableUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminGetUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminInitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminResetUserPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminSetUserPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminUserGlobalSignOutRequest;
use AsyncAws\CognitoIdentityProvider\Input\AssociateSoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Input\ChangePasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\ConfirmForgotPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\ConfirmSignUpRequest;
use AsyncAws\CognitoIdentityProvider\Input\ForgotPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\GetUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\InitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\CognitoIdentityProvider\Input\ResendConfirmationCodeRequest;
use AsyncAws\CognitoIdentityProvider\Input\RespondToAuthChallengeRequest;
use AsyncAws\CognitoIdentityProvider\Input\SetUserMFAPreferenceRequest;
use AsyncAws\CognitoIdentityProvider\Input\SignUpRequest;
use AsyncAws\CognitoIdentityProvider\Input\VerifySoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Result\AdminConfirmSignUpResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminCreateUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminDisableUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminEnableUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminGetUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminInitiateAuthResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminResetUserPasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminSetUserPasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminUpdateUserAttributesResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminUserGlobalSignOutResponse;
use AsyncAws\CognitoIdentityProvider\Result\AssociateSoftwareTokenResponse;
use AsyncAws\CognitoIdentityProvider\Result\ChangePasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\ConfirmForgotPasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\ConfirmSignUpResponse;
use AsyncAws\CognitoIdentityProvider\Result\ForgotPasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\GetUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\InitiateAuthResponse;
use AsyncAws\CognitoIdentityProvider\Result\ListUsersResponse;
use AsyncAws\CognitoIdentityProvider\Result\ResendConfirmationCodeResponse;
use AsyncAws\CognitoIdentityProvider\Result\RespondToAuthChallengeResponse;
use AsyncAws\CognitoIdentityProvider\Result\SetUserMFAPreferenceResponse;
use AsyncAws\CognitoIdentityProvider\Result\SignUpResponse;
use AsyncAws\CognitoIdentityProvider\Result\VerifySoftwareTokenResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CognitoIdentityProviderClientTest extends TestCase
{
    public function testAdminConfirmSignUp(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminConfirmSignUpRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->AdminConfirmSignUp($input);

        self::assertInstanceOf(AdminConfirmSignUpResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminCreateUser(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminCreateUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',

        ]);
        $result = $client->AdminCreateUser($input);

        self::assertInstanceOf(AdminCreateUserResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminDeleteUser(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminDeleteUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->AdminDeleteUser($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminDisableUser(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminDisableUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->adminDisableUser($input);

        self::assertInstanceOf(AdminDisableUserResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminEnableUser(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminEnableUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->adminEnableUser($input);

        self::assertInstanceOf(AdminEnableUserResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminGetUser(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminGetUserRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->AdminGetUser($input);

        self::assertInstanceOf(AdminGetUserResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminInitiateAuth(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminInitiateAuthRequest([
            'UserPoolId' => 'change me',
            'ClientId' => 'change me',
            'AuthFlow' => AuthFlowType::CUSTOM_AUTH,

        ]);
        $result = $client->AdminInitiateAuth($input);

        self::assertInstanceOf(AdminInitiateAuthResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminResetUserPassword(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminResetUserPasswordRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',

        ]);
        $result = $client->adminResetUserPassword($input);

        self::assertInstanceOf(AdminResetUserPasswordResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAdminSetUserPassword(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminSetUserPasswordRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
            'Password' => 'change me',
        ]);
        $result = $client->AdminSetUserPassword($input);

        self::assertInstanceOf(AdminSetUserPasswordResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

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

    public function testAdminUserGlobalSignOut(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AdminUserGlobalSignOutRequest([
            'UserPoolId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->adminUserGlobalSignOut($input);

        self::assertInstanceOf(AdminUserGlobalSignOutResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testAssociateSoftwareToken(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new AssociateSoftwareTokenRequest([

        ]);
        $result = $client->AssociateSoftwareToken($input);

        self::assertInstanceOf(AssociateSoftwareTokenResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testChangePassword(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new ChangePasswordRequest([
            'PreviousPassword' => 'change me',
            'ProposedPassword' => 'change me',
            'AccessToken' => 'change me',
        ]);
        $result = $client->ChangePassword($input);

        self::assertInstanceOf(ChangePasswordResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testConfirmForgotPassword(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new ConfirmForgotPasswordRequest([
            'ClientId' => 'change me',
            'Username' => 'change me',
            'ConfirmationCode' => 'change me',
            'Password' => 'change me',
        ]);
        $result = $client->ConfirmForgotPassword($input);

        self::assertInstanceOf(ConfirmForgotPasswordResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testConfirmSignUp(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new ConfirmSignUpRequest([
            'ClientId' => 'change me',

            'Username' => 'change me',
            'ConfirmationCode' => 'change me',

        ]);
        $result = $client->confirmSignUp($input);

        self::assertInstanceOf(ConfirmSignUpResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testForgotPassword(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new ForgotPasswordRequest([
            'ClientId' => 'change me',
            'Username' => 'change me',
        ]);
        $result = $client->ForgotPassword($input);

        self::assertInstanceOf(ForgotPasswordResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetUser(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new GetUserRequest([
            'AccessToken' => 'change me',
        ]);
        $result = $client->getUser($input);

        self::assertInstanceOf(GetUserResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testInitiateAuth(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new InitiateAuthRequest([
            'AuthFlow' => 'USER_PASSWORD_AUTH',
            'ClientId' => 'abc123',
        ]);
        $result = $client->InitiateAuth($input);

        self::assertInstanceOf(InitiateAuthResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListUsers(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new ListUsersRequest([
            'UserPoolId' => 'change me',

        ]);
        $result = $client->ListUsers($input);

        self::assertInstanceOf(ListUsersResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testResendConfirmationCode(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new ResendConfirmationCodeRequest([
            'ClientId' => 'id123',
            'Username' => 'stickman',
        ]);
        $result = $client->ResendConfirmationCode($input);

        self::assertInstanceOf(ResendConfirmationCodeResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testRespondToAuthChallenge(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new RespondToAuthChallengeRequest([
            'ClientId' => 'change me',
            'ChallengeName' => 'ADMIN_NO_SRP_AUTH',
        ]);
        $result = $client->RespondToAuthChallenge($input);

        self::assertInstanceOf(RespondToAuthChallengeResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSetUserMFAPreference(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new SetUserMFAPreferenceRequest([

            'AccessToken' => 'change me',
        ]);
        $result = $client->SetUserMFAPreference($input);

        self::assertInstanceOf(SetUserMFAPreferenceResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSignUp(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new SignUpRequest([
            'ClientId' => 'secret client id',
            'Username' => 'stickman',
            'Password' => 'Passw0rd!',
        ]);
        $result = $client->SignUp($input);

        self::assertInstanceOf(SignUpResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testVerifySoftwareToken(): void
    {
        $client = new CognitoIdentityProviderClient([], new NullProvider(), new MockHttpClient());

        $input = new VerifySoftwareTokenRequest([

            'UserCode' => 'change me',

        ]);
        $result = $client->VerifySoftwareToken($input);

        self::assertInstanceOf(VerifySoftwareTokenResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
