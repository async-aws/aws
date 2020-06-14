<?php

namespace AsyncAws\CognitoIdentityProvider;

use AsyncAws\CognitoIdentityProvider\Input\AdminCreateUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminDeleteUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminGetUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminInitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\Input\AssociateSoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Input\ChangePasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\CognitoIdentityProvider\Input\SetUserMFAPreferenceRequest;
use AsyncAws\CognitoIdentityProvider\Input\SignUpRequest;
use AsyncAws\CognitoIdentityProvider\Input\VerifySoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Result\AdminCreateUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminGetUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminInitiateAuthResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminUpdateUserAttributesResponse;
use AsyncAws\CognitoIdentityProvider\Result\AssociateSoftwareTokenResponse;
use AsyncAws\CognitoIdentityProvider\Result\ChangePasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\ListUsersResponse;
use AsyncAws\CognitoIdentityProvider\Result\SetUserMFAPreferenceResponse;
use AsyncAws\CognitoIdentityProvider\Result\SignUpResponse;
use AsyncAws\CognitoIdentityProvider\Result\VerifySoftwareTokenResponse;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;

class CognitoIdentityProviderClient extends AbstractApi
{
    /**
     * Creates a new user in the specified user pool.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admincreateuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   UserAttributes?: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   ValidationData?: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   TemporaryPassword?: string,
     *   ForceAliasCreation?: bool,
     *   MessageAction?: \AsyncAws\CognitoIdentityProvider\Enum\MessageActionType::*,
     *   DesiredDeliveryMediums?: list<\AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType::*>,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * }|AdminCreateUserRequest $input
     */
    public function adminCreateUser($input): AdminCreateUserResponse
    {
        $input = AdminCreateUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminCreateUser', 'region' => $input->getRegion()]));

        return new AdminCreateUserResponse($response);
    }

    /**
     * Deletes a user as an administrator. Works on any user.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admindeleteuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   @region?: string,
     * }|AdminDeleteUserRequest $input
     */
    public function adminDeleteUser($input): Result
    {
        $input = AdminDeleteUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminDeleteUser', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Gets the specified user by user name in a user pool as an administrator. Works on any user.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admingetuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   @region?: string,
     * }|AdminGetUserRequest $input
     */
    public function adminGetUser($input): AdminGetUserResponse
    {
        $input = AdminGetUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminGetUser', 'region' => $input->getRegion()]));

        return new AdminGetUserResponse($response);
    }

    /**
     * Initiates the authentication flow, as an administrator.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admininitiateauth
     *
     * @param array{
     *   UserPoolId: string,
     *   ClientId: string,
     *   AuthFlow: \AsyncAws\CognitoIdentityProvider\Enum\AuthFlowType::*,
     *   AuthParameters?: array<string, string>,
     *   ClientMetadata?: array<string, string>,
     *   AnalyticsMetadata?: \AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType|array,
     *   ContextData?: \AsyncAws\CognitoIdentityProvider\ValueObject\ContextDataType|array,
     *   @region?: string,
     * }|AdminInitiateAuthRequest $input
     */
    public function adminInitiateAuth($input): AdminInitiateAuthResponse
    {
        $input = AdminInitiateAuthRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminInitiateAuth', 'region' => $input->getRegion()]));

        return new AdminInitiateAuthResponse($response);
    }

    /**
     * Updates the specified user's attributes, including developer attributes, as an administrator. Works on any user.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminupdateuserattributes
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   UserAttributes: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * }|AdminUpdateUserAttributesRequest $input
     */
    public function adminUpdateUserAttributes($input): AdminUpdateUserAttributesResponse
    {
        $input = AdminUpdateUserAttributesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminUpdateUserAttributes', 'region' => $input->getRegion()]));

        return new AdminUpdateUserAttributesResponse($response);
    }

    /**
     * Returns a unique generated shared secret key code for the user account. The request takes an access token or a
     * session string, but not both.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#associatesoftwaretoken
     *
     * @param array{
     *   AccessToken?: string,
     *   Session?: string,
     *   @region?: string,
     * }|AssociateSoftwareTokenRequest $input
     */
    public function associateSoftwareToken($input = []): AssociateSoftwareTokenResponse
    {
        $input = AssociateSoftwareTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AssociateSoftwareToken', 'region' => $input->getRegion()]));

        return new AssociateSoftwareTokenResponse($response);
    }

    /**
     * Changes the password for a specified user in a user pool.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#changepassword
     *
     * @param array{
     *   PreviousPassword: string,
     *   ProposedPassword: string,
     *   AccessToken: string,
     *   @region?: string,
     * }|ChangePasswordRequest $input
     */
    public function changePassword($input): ChangePasswordResponse
    {
        $input = ChangePasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangePassword', 'region' => $input->getRegion()]));

        return new ChangePasswordResponse($response);
    }

    /**
     * Lists the users in the Amazon Cognito user pool.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#listusers
     *
     * @param array{
     *   UserPoolId: string,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   PaginationToken?: string,
     *   Filter?: string,
     *   @region?: string,
     * }|ListUsersRequest $input
     */
    public function listUsers($input): ListUsersResponse
    {
        $input = ListUsersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListUsers', 'region' => $input->getRegion()]));

        return new ListUsersResponse($response, $this, $input);
    }

    /**
     * Set the user's multi-factor authentication (MFA) method preference, including which MFA factors are enabled and if
     * any are preferred. Only one factor can be set as preferred. The preferred MFA factor will be used to authenticate a
     * user if multiple factors are enabled. If multiple options are enabled and no preference is set, a challenge to choose
     * an MFA option will be returned during sign in.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#setusermfapreference
     *
     * @param array{
     *   SMSMfaSettings?: \AsyncAws\CognitoIdentityProvider\ValueObject\SMSMfaSettingsType|array,
     *   SoftwareTokenMfaSettings?: \AsyncAws\CognitoIdentityProvider\ValueObject\SoftwareTokenMfaSettingsType|array,
     *   AccessToken: string,
     *   @region?: string,
     * }|SetUserMFAPreferenceRequest $input
     */
    public function setUserMFAPreference($input): SetUserMFAPreferenceResponse
    {
        $input = SetUserMFAPreferenceRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SetUserMFAPreference', 'region' => $input->getRegion()]));

        return new SetUserMFAPreferenceResponse($response);
    }

    /**
     * Registers the user in the specified user pool and creates a user name, password, and user attributes.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#signup
     *
     * @param array{
     *   ClientId: string,
     *   SecretHash?: string,
     *   Username: string,
     *   Password: string,
     *   UserAttributes?: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   ValidationData?: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   AnalyticsMetadata?: \AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType|array,
     *   UserContextData?: \AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType|array,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * }|SignUpRequest $input
     */
    public function signUp($input): SignUpResponse
    {
        $input = SignUpRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SignUp', 'region' => $input->getRegion()]));

        return new SignUpResponse($response);
    }

    /**
     * Use this API to register a user's entered TOTP code and mark the user's software token MFA status as "verified" if
     * successful. The request takes an access token or a session string, but not both.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#verifysoftwaretoken
     *
     * @param array{
     *   AccessToken?: string,
     *   Session?: string,
     *   UserCode: string,
     *   FriendlyDeviceName?: string,
     *   @region?: string,
     * }|VerifySoftwareTokenRequest $input
     */
    public function verifySoftwareToken($input): VerifySoftwareTokenResponse
    {
        $input = VerifySoftwareTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'VerifySoftwareToken', 'region' => $input->getRegion()]));

        return new VerifySoftwareTokenResponse($response);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-2':
                return [
                    'endpoint' => "https://cognito-idp.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'cognito-idp',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://cognito-idp.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'cognito-idp',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://cognito-idp-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'cognito-idp',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://cognito-idp-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'cognito-idp',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://cognito-idp-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'cognito-idp',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://cognito-idp-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'cognito-idp',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "CognitoIdentityProvider".', $region));
    }
}
