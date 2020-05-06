<?php

namespace AsyncAws\CognitoIdentityProvider;

use AsyncAws\CognitoIdentityProvider\Input\AdminCreateUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminDeleteUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminGetUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\Input\AssociateSoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Input\ChangePasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\CognitoIdentityProvider\Input\SetUserMFAPreferenceRequest;
use AsyncAws\CognitoIdentityProvider\Input\VerifySoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Result\AdminCreateUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminGetUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\AdminUpdateUserAttributesResponse;
use AsyncAws\CognitoIdentityProvider\Result\AssociateSoftwareTokenResponse;
use AsyncAws\CognitoIdentityProvider\Result\ChangePasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\ListUsersResponse;
use AsyncAws\CognitoIdentityProvider\Result\SetUserMFAPreferenceResponse;
use AsyncAws\CognitoIdentityProvider\Result\VerifySoftwareTokenResponse;
use AsyncAws\Core\AbstractApi;
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
     *   ClientMetadata?: string[],
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
     * Updates the specified user's attributes, including developer attributes, as an administrator. Works on any user.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminupdateuserattributes
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   UserAttributes: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   ClientMetadata?: string[],
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

    protected function getServiceCode(): string
    {
        return 'cognito-idp';
    }

    protected function getSignatureScopeName(): string
    {
        return 'cognito-idp';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
