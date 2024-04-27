<?php

namespace AsyncAws\CognitoIdentityProvider;

use AsyncAws\CognitoIdentityProvider\Enum\AuthFlowType;
use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;
use AsyncAws\CognitoIdentityProvider\Enum\MessageActionType;
use AsyncAws\CognitoIdentityProvider\Exception\AliasExistsException;
use AsyncAws\CognitoIdentityProvider\Exception\CodeDeliveryFailureException;
use AsyncAws\CognitoIdentityProvider\Exception\CodeMismatchException;
use AsyncAws\CognitoIdentityProvider\Exception\ConcurrentModificationException;
use AsyncAws\CognitoIdentityProvider\Exception\EnableSoftwareTokenMFAException;
use AsyncAws\CognitoIdentityProvider\Exception\ExpiredCodeException;
use AsyncAws\CognitoIdentityProvider\Exception\ForbiddenException;
use AsyncAws\CognitoIdentityProvider\Exception\GroupExistsException;
use AsyncAws\CognitoIdentityProvider\Exception\InternalErrorException;
use AsyncAws\CognitoIdentityProvider\Exception\InvalidEmailRoleAccessPolicyException;
use AsyncAws\CognitoIdentityProvider\Exception\InvalidLambdaResponseException;
use AsyncAws\CognitoIdentityProvider\Exception\InvalidParameterException;
use AsyncAws\CognitoIdentityProvider\Exception\InvalidPasswordException;
use AsyncAws\CognitoIdentityProvider\Exception\InvalidSmsRoleAccessPolicyException;
use AsyncAws\CognitoIdentityProvider\Exception\InvalidSmsRoleTrustRelationshipException;
use AsyncAws\CognitoIdentityProvider\Exception\InvalidUserPoolConfigurationException;
use AsyncAws\CognitoIdentityProvider\Exception\LimitExceededException;
use AsyncAws\CognitoIdentityProvider\Exception\MFAMethodNotFoundException;
use AsyncAws\CognitoIdentityProvider\Exception\NotAuthorizedException;
use AsyncAws\CognitoIdentityProvider\Exception\PasswordResetRequiredException;
use AsyncAws\CognitoIdentityProvider\Exception\PreconditionNotMetException;
use AsyncAws\CognitoIdentityProvider\Exception\ResourceNotFoundException;
use AsyncAws\CognitoIdentityProvider\Exception\SoftwareTokenMFANotFoundException;
use AsyncAws\CognitoIdentityProvider\Exception\TooManyFailedAttemptsException;
use AsyncAws\CognitoIdentityProvider\Exception\TooManyRequestsException;
use AsyncAws\CognitoIdentityProvider\Exception\UnauthorizedException;
use AsyncAws\CognitoIdentityProvider\Exception\UnexpectedLambdaException;
use AsyncAws\CognitoIdentityProvider\Exception\UnsupportedOperationException;
use AsyncAws\CognitoIdentityProvider\Exception\UnsupportedTokenTypeException;
use AsyncAws\CognitoIdentityProvider\Exception\UnsupportedUserStateException;
use AsyncAws\CognitoIdentityProvider\Exception\UserLambdaValidationException;
use AsyncAws\CognitoIdentityProvider\Exception\UsernameExistsException;
use AsyncAws\CognitoIdentityProvider\Exception\UserNotConfirmedException;
use AsyncAws\CognitoIdentityProvider\Exception\UserNotFoundException;
use AsyncAws\CognitoIdentityProvider\Input\AdminAddUserToGroupRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminConfirmSignUpRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminCreateUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminDeleteUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminDisableUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminEnableUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminGetUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminInitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminRemoveUserFromGroupRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminResetUserPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminSetUserPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminUpdateUserAttributesRequest;
use AsyncAws\CognitoIdentityProvider\Input\AdminUserGlobalSignOutRequest;
use AsyncAws\CognitoIdentityProvider\Input\AssociateSoftwareTokenRequest;
use AsyncAws\CognitoIdentityProvider\Input\ChangePasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\ConfirmForgotPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\ConfirmSignUpRequest;
use AsyncAws\CognitoIdentityProvider\Input\CreateGroupRequest;
use AsyncAws\CognitoIdentityProvider\Input\ForgotPasswordRequest;
use AsyncAws\CognitoIdentityProvider\Input\GetUserRequest;
use AsyncAws\CognitoIdentityProvider\Input\InitiateAuthRequest;
use AsyncAws\CognitoIdentityProvider\Input\ListGroupsRequest;
use AsyncAws\CognitoIdentityProvider\Input\ListUsersRequest;
use AsyncAws\CognitoIdentityProvider\Input\ResendConfirmationCodeRequest;
use AsyncAws\CognitoIdentityProvider\Input\RespondToAuthChallengeRequest;
use AsyncAws\CognitoIdentityProvider\Input\RevokeTokenRequest;
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
use AsyncAws\CognitoIdentityProvider\Result\CreateGroupResponse;
use AsyncAws\CognitoIdentityProvider\Result\ForgotPasswordResponse;
use AsyncAws\CognitoIdentityProvider\Result\GetUserResponse;
use AsyncAws\CognitoIdentityProvider\Result\InitiateAuthResponse;
use AsyncAws\CognitoIdentityProvider\Result\ListGroupsResponse;
use AsyncAws\CognitoIdentityProvider\Result\ListUsersResponse;
use AsyncAws\CognitoIdentityProvider\Result\ResendConfirmationCodeResponse;
use AsyncAws\CognitoIdentityProvider\Result\RespondToAuthChallengeResponse;
use AsyncAws\CognitoIdentityProvider\Result\RevokeTokenResponse;
use AsyncAws\CognitoIdentityProvider\Result\SetUserMFAPreferenceResponse;
use AsyncAws\CognitoIdentityProvider\Result\SignUpResponse;
use AsyncAws\CognitoIdentityProvider\Result\VerifySoftwareTokenResponse;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\ContextDataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\SMSMfaSettingsType;
use AsyncAws\CognitoIdentityProvider\ValueObject\SoftwareTokenMfaSettingsType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;

class CognitoIdentityProviderClient extends AbstractApi
{
    /**
     * Adds a user to a group. A user who is in a group can present a preferred-role claim to an identity pool, and
     * populates a `cognito:groups` claim to their access and identity tokens.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminAddUserToGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminaddusertogroup
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   GroupName: string,
     *   '@region'?: string|null,
     * }|AdminAddUserToGroupRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminAddUserToGroup($input): Result
    {
        $input = AdminAddUserToGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminAddUserToGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new Result($response);
    }

    /**
     * This IAM-authenticated API operation provides a code that Amazon Cognito sent to your user when they signed up in
     * your user pool. After your user enters their code, they confirm ownership of the email address or phone number that
     * they provided, and their user account becomes active. Depending on your user pool configuration, your users will
     * receive their confirmation code in an email or SMS message.
     *
     * Local users who signed up in your user pool are the only type of user who can confirm sign-up with a code. Users who
     * federate through an external identity provider (IdP) have already been confirmed by their IdP. Administrator-created
     * users confirm their accounts when they respond to their invitation email message and choose a password.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminConfirmSignUp.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminconfirmsignup
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|AdminConfirmSignUpRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws NotAuthorizedException
     * @throws TooManyFailedAttemptsException
     * @throws InvalidLambdaResponseException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminConfirmSignUp($input): AdminConfirmSignUpResponse
    {
        $input = AdminConfirmSignUpRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminConfirmSignUp', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'TooManyFailedAttemptsException' => TooManyFailedAttemptsException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new AdminConfirmSignUpResponse($response);
    }

    /**
     * Creates a new user in the specified user pool.
     *
     * If `MessageAction` isn't set, the default is to send a welcome message via email or phone (SMS).
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^1]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^2]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^3] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * This message is based on a template that you configured in your call to create or update a user pool. This template
     * includes your custom sign-up instructions and placeholders for user name and temporary password.
     *
     * Alternatively, you can call `AdminCreateUser` with `SUPPRESS` for the `MessageAction` parameter, and Amazon Cognito
     * won't send any email.
     *
     * In either case, the user will be in the `FORCE_CHANGE_PASSWORD` state until they sign in and change their password.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^4]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^5]
     * >
     *
     * [^1]: https://console.aws.amazon.com/pinpoint/home/
     * [^2]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     * [^4]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminCreateUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admincreateuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   UserAttributes?: null|array<AttributeType|array>,
     *   ValidationData?: null|array<AttributeType|array>,
     *   TemporaryPassword?: null|string,
     *   ForceAliasCreation?: null|bool,
     *   MessageAction?: null|MessageActionType::*,
     *   DesiredDeliveryMediums?: null|array<DeliveryMediumType::*>,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|AdminCreateUserRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UserNotFoundException
     * @throws UsernameExistsException
     * @throws InvalidPasswordException
     * @throws CodeDeliveryFailureException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws InvalidLambdaResponseException
     * @throws PreconditionNotMetException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UnsupportedUserStateException
     * @throws InternalErrorException
     */
    public function adminCreateUser($input): AdminCreateUserResponse
    {
        $input = AdminCreateUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminCreateUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UsernameExistsException' => UsernameExistsException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'PreconditionNotMetException' => PreconditionNotMetException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UnsupportedUserStateException' => UnsupportedUserStateException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new AdminCreateUserResponse($response);
    }

    /**
     * Deletes a user as an administrator. Works on any user.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminDeleteUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admindeleteuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   '@region'?: string|null,
     * }|AdminDeleteUserRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminDeleteUser($input): Result
    {
        $input = AdminDeleteUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminDeleteUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deactivates a user and revokes all access tokens for the user. A deactivated user can't sign in, but still appears in
     * the responses to `GetUser` and `ListUsers` API requests.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminDisableUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admindisableuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   '@region'?: string|null,
     * }|AdminDisableUserRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminDisableUser($input): AdminDisableUserResponse
    {
        $input = AdminDisableUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminDisableUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new AdminDisableUserResponse($response);
    }

    /**
     * Enables the specified user as an administrator. Works on any user.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminEnableUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminenableuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   '@region'?: string|null,
     * }|AdminEnableUserRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminEnableUser($input): AdminEnableUserResponse
    {
        $input = AdminEnableUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminEnableUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new AdminEnableUserResponse($response);
    }

    /**
     * Gets the specified user by user name in a user pool as an administrator. Works on any user.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminGetUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admingetuser
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   '@region'?: string|null,
     * }|AdminGetUserRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminGetUser($input): AdminGetUserResponse
    {
        $input = AdminGetUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminGetUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new AdminGetUserResponse($response);
    }

    /**
     * Initiates the authentication flow, as an administrator.
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^1]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^2]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^3] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^4]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^5]
     * >
     *
     * [^1]: https://console.aws.amazon.com/pinpoint/home/
     * [^2]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     * [^4]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminInitiateAuth.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#admininitiateauth
     *
     * @param array{
     *   UserPoolId: string,
     *   ClientId: string,
     *   AuthFlow: AuthFlowType::*,
     *   AuthParameters?: null|array<string, string>,
     *   ClientMetadata?: null|array<string, string>,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   ContextData?: null|ContextDataType|array,
     *   '@region'?: string|null,
     * }|AdminInitiateAuthRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws TooManyRequestsException
     * @throws InternalErrorException
     * @throws UnexpectedLambdaException
     * @throws InvalidUserPoolConfigurationException
     * @throws UserLambdaValidationException
     * @throws InvalidLambdaResponseException
     * @throws MFAMethodNotFoundException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws PasswordResetRequiredException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     */
    public function adminInitiateAuth($input): AdminInitiateAuthResponse
    {
        $input = AdminInitiateAuthRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminInitiateAuth', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InternalErrorException' => InternalErrorException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'MFAMethodNotFoundException' => MFAMethodNotFoundException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
        ]]));

        return new AdminInitiateAuthResponse($response);
    }

    /**
     * Removes the specified user from the specified group.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminRemoveUserFromGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminremoveuserfromgroup
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   GroupName: string,
     *   '@region'?: string|null,
     * }|AdminRemoveUserFromGroupRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminRemoveUserFromGroup($input): Result
    {
        $input = AdminRemoveUserFromGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminRemoveUserFromGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Resets the specified user's password in a user pool as an administrator. Works on any user.
     *
     * To use this API operation, your user pool must have self-service account recovery configured. Use
     * AdminSetUserPassword [^1] if you manage passwords as an administrator.
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^2]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^3]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^4] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * Deactivates a user's password, requiring them to change it. If a user tries to sign in after the API is called,
     * Amazon Cognito responds with a `PasswordResetRequiredException` error. Your app must then perform the actions that
     * reset your user's password: the forgot-password flow. In addition, if the user pool has phone verification selected
     * and a verified phone number exists for the user, or if email verification is selected and a verified email exists for
     * the user, calling this API will also result in sending a message to the end user with the code to change their
     * password.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^5]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^6]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminSetUserPassword.html
     * [^2]: https://console.aws.amazon.com/pinpoint/home/
     * [^3]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     * [^5]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^6]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminResetUserPassword.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminresetuserpassword
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|AdminResetUserPasswordRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws NotAuthorizedException
     * @throws InvalidLambdaResponseException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws UserNotFoundException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws InternalErrorException
     */
    public function adminResetUserPassword($input): AdminResetUserPasswordResponse
    {
        $input = AdminResetUserPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminResetUserPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new AdminResetUserPasswordResponse($response);
    }

    /**
     * Sets the specified user's password in a user pool as an administrator. Works on any user.
     *
     * The password can be temporary or permanent. If it is temporary, the user status enters the `FORCE_CHANGE_PASSWORD`
     * state. When the user next tries to sign in, the InitiateAuth/AdminInitiateAuth response will contain the
     * `NEW_PASSWORD_REQUIRED` challenge. If the user doesn't sign in before it expires, the user won't be able to sign in,
     * and an administrator must reset their password.
     *
     * Once the user has set a new password, or the password is permanent, the user status is set to `Confirmed`.
     *
     * `AdminSetUserPassword` can set a password for the user profile that Amazon Cognito creates for third-party federated
     * users. When you set a password, the federated user's status changes from `EXTERNAL_PROVIDER` to `CONFIRMED`. A user
     * in this state can sign in as a federated user, and initiate authentication flows in the API like a linked native
     * user. They can also modify their password and attributes in token-authenticated API requests like `ChangePassword`
     * and `UpdateUserAttributes`. As a best security practice and to keep users in sync with your external IdP, don't set
     * passwords on federated user profiles. To set up a federated user for native sign-in with a linked native user, refer
     * to Linking federated users to an existing user profile [^1].
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^2]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^3]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pools-identity-federation-consolidate-users.html
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminSetUserPassword.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminsetuserpassword
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   Password: string,
     *   Permanent?: null|bool,
     *   '@region'?: string|null,
     * }|AdminSetUserPasswordRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     * @throws TooManyRequestsException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     */
    public function adminSetUserPassword($input): AdminSetUserPasswordResponse
    {
        $input = AdminSetUserPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminSetUserPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
        ]]));

        return new AdminSetUserPasswordResponse($response);
    }

    /**
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^1]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^2]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^3] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * Updates the specified user's attributes, including developer attributes, as an administrator. Works on any user. To
     * delete an attribute from your user, submit the attribute in your API request with a blank value.
     *
     * For custom attributes, you must prepend the `custom:` prefix to the attribute name.
     *
     * In addition to updating user attributes, this API can also be used to mark phone and email as verified.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^4]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^5]
     * >
     *
     * [^1]: https://console.aws.amazon.com/pinpoint/home/
     * [^2]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     * [^4]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminUpdateUserAttributes.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminupdateuserattributes
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   UserAttributes: array<AttributeType|array>,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|AdminUpdateUserAttributesRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws InvalidLambdaResponseException
     * @throws AliasExistsException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     */
    public function adminUpdateUserAttributes($input): AdminUpdateUserAttributesResponse
    {
        $input = AdminUpdateUserAttributesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminUpdateUserAttributes', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'AliasExistsException' => AliasExistsException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
        ]]));

        return new AdminUpdateUserAttributesResponse($response);
    }

    /**
     * Invalidates the identity, access, and refresh tokens that Amazon Cognito issued to a user. Call this operation with
     * your administrative credentials when your user signs out of your app. This results in the following behavior.
     *
     * - Amazon Cognito no longer accepts *token-authorized* user operations that you authorize with a signed-out user's
     *   access tokens. For more information, see Using the Amazon Cognito user pools API and user pool endpoints [^1].
     *
     *   Amazon Cognito returns an `Access Token has been revoked` error when your app attempts to authorize a user pools
     *   API request with a revoked access token that contains the scope `aws.cognito.signin.user.admin`.
     * - Amazon Cognito no longer accepts a signed-out user's ID token in a GetId [^2] request to an identity pool with
     *   `ServerSideTokenCheck` enabled for its user pool IdP configuration in CognitoIdentityProvider [^3].
     * - Amazon Cognito no longer accepts a signed-out user's refresh tokens in refresh requests.
     *
     * Other requests might be valid until your user's token expires.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^4]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^5]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^2]: https://docs.aws.amazon.com/cognitoidentity/latest/APIReference/API_GetId.html
     * [^3]: https://docs.aws.amazon.com/cognitoidentity/latest/APIReference/API_CognitoIdentityProvider.html
     * [^4]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminUserGlobalSignOut.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#adminuserglobalsignout
     *
     * @param array{
     *   UserPoolId: string,
     *   Username: string,
     *   '@region'?: string|null,
     * }|AdminUserGlobalSignOutRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     */
    public function adminUserGlobalSignOut($input): AdminUserGlobalSignOutResponse
    {
        $input = AdminUserGlobalSignOutRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminUserGlobalSignOut', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new AdminUserGlobalSignOutResponse($response);
    }

    /**
     * Begins setup of time-based one-time password (TOTP) multi-factor authentication (MFA) for a user, with a unique
     * private key that Amazon Cognito generates and returns in the API response. You can authorize an
     * `AssociateSoftwareToken` request with either the user's access token, or a session string from a challenge response
     * that you received from Amazon Cognito.
     *
     * > Amazon Cognito disassociates an existing software token when you verify the new token in a VerifySoftwareToken [^1]
     * > API request. If you don't verify the software token and your user pool doesn't require MFA, the user can then
     * > authenticate with user name and password credentials alone. If your user pool requires TOTP MFA, Amazon Cognito
     * > generates an `MFA_SETUP` or `SOFTWARE_TOKEN_SETUP` challenge each time your user signs. Complete setup with
     * > `AssociateSoftwareToken` and `VerifySoftwareToken`.
     * >
     * > After you set up software token MFA for your user, Amazon Cognito generates a `SOFTWARE_TOKEN_MFA` challenge when
     * > they authenticate. Respond to this challenge with your user's TOTP.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^2].
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_VerifySoftwareToken.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AssociateSoftwareToken.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#associatesoftwaretoken
     *
     * @param array{
     *   AccessToken?: null|string,
     *   Session?: null|string,
     *   '@region'?: string|null,
     * }|AssociateSoftwareTokenRequest $input
     *
     * @throws ConcurrentModificationException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws InternalErrorException
     * @throws SoftwareTokenMFANotFoundException
     * @throws ForbiddenException
     */
    public function associateSoftwareToken($input = []): AssociateSoftwareTokenResponse
    {
        $input = AssociateSoftwareTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AssociateSoftwareToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
            'SoftwareTokenMFANotFoundException' => SoftwareTokenMFANotFoundException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new AssociateSoftwareTokenResponse($response);
    }

    /**
     * Changes the password for a specified user in a user pool.
     *
     * Authorize this action with a signed-in user's access token. It must include the scope
     * `aws.cognito.signin.user.admin`.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ChangePassword.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#changepassword
     *
     * @param array{
     *   PreviousPassword: string,
     *   ProposedPassword: string,
     *   AccessToken: string,
     *   '@region'?: string|null,
     * }|ChangePasswordRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws NotAuthorizedException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws PasswordResetRequiredException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     * @throws InternalErrorException
     * @throws ForbiddenException
     */
    public function changePassword($input): ChangePasswordResponse
    {
        $input = ChangePasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangePassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'InternalErrorException' => InternalErrorException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new ChangePasswordResponse($response);
    }

    /**
     * Allows a user to enter a confirmation code to reset a forgotten password.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ConfirmForgotPassword.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#confirmforgotpassword
     *
     * @param array{
     *   ClientId: string,
     *   SecretHash?: null|string,
     *   Username: string,
     *   ConfirmationCode: string,
     *   Password: string,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|ConfirmForgotPasswordRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws NotAuthorizedException
     * @throws CodeMismatchException
     * @throws ExpiredCodeException
     * @throws TooManyFailedAttemptsException
     * @throws InvalidLambdaResponseException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     * @throws InternalErrorException
     * @throws ForbiddenException
     */
    public function confirmForgotPassword($input): ConfirmForgotPasswordResponse
    {
        $input = ConfirmForgotPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ConfirmForgotPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'CodeMismatchException' => CodeMismatchException::class,
            'ExpiredCodeException' => ExpiredCodeException::class,
            'TooManyFailedAttemptsException' => TooManyFailedAttemptsException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'InternalErrorException' => InternalErrorException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new ConfirmForgotPasswordResponse($response);
    }

    /**
     * This public API operation provides a code that Amazon Cognito sent to your user when they signed up in your user pool
     * via the SignUp [^1] API operation. After your user enters their code, they confirm ownership of the email address or
     * phone number that they provided, and their user account becomes active. Depending on your user pool configuration,
     * your users will receive their confirmation code in an email or SMS message.
     *
     * Local users who signed up in your user pool are the only type of user who can confirm sign-up with a code. Users who
     * federate through an external identity provider (IdP) have already been confirmed by their IdP. Administrator-created
     * users, users created with the AdminCreateUser [^2] API operation, confirm their accounts when they respond to their
     * invitation email message and choose a password. They do not receive a confirmation code. Instead, they receive a
     * temporary password.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^3].
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_SignUp.html
     * [^2]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminCreateUser.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ConfirmSignUp.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#confirmsignup
     *
     * @param array{
     *   ClientId: string,
     *   SecretHash?: null|string,
     *   Username: string,
     *   ConfirmationCode: string,
     *   ForceAliasCreation?: null|bool,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|ConfirmSignUpRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws NotAuthorizedException
     * @throws TooManyFailedAttemptsException
     * @throws CodeMismatchException
     * @throws ExpiredCodeException
     * @throws InvalidLambdaResponseException
     * @throws AliasExistsException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     * @throws ForbiddenException
     */
    public function confirmSignUp($input): ConfirmSignUpResponse
    {
        $input = ConfirmSignUpRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ConfirmSignUp', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'TooManyFailedAttemptsException' => TooManyFailedAttemptsException::class,
            'CodeMismatchException' => CodeMismatchException::class,
            'ExpiredCodeException' => ExpiredCodeException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'AliasExistsException' => AliasExistsException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new ConfirmSignUpResponse($response);
    }

    /**
     * Creates a new group in the specified user pool.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_CreateGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#creategroup
     *
     * @param array{
     *   GroupName: string,
     *   UserPoolId: string,
     *   Description?: null|string,
     *   RoleArn?: null|string,
     *   Precedence?: null|int,
     *   '@region'?: string|null,
     * }|CreateGroupRequest $input
     *
     * @throws InvalidParameterException
     * @throws GroupExistsException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws InternalErrorException
     */
    public function createGroup($input): CreateGroupResponse
    {
        $input = CreateGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'GroupExistsException' => GroupExistsException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new CreateGroupResponse($response);
    }

    /**
     * Calling this API causes a message to be sent to the end user with a confirmation code that is required to change the
     * user's password. For the `Username` parameter, you can use the username or user alias. The method used to send the
     * confirmation code is sent according to the specified AccountRecoverySetting. For more information, see Recovering
     * User Accounts [^1] in the *Amazon Cognito Developer Guide*. To use the confirmation code for resetting the password,
     * call ConfirmForgotPassword [^2].
     *
     * If neither a verified phone number nor a verified email exists, this API returns `InvalidParameterException`. If your
     * app client has a client secret and you don't provide a `SECRET_HASH` parameter, this API returns
     * `NotAuthorizedException`.
     *
     * To use this API operation, your user pool must have self-service account recovery configured. Use
     * AdminSetUserPassword [^3] if you manage passwords as an administrator.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^4].
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^5]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^6]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^7] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/how-to-recover-a-user-account.html
     * [^2]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ConfirmForgotPassword.html
     * [^3]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminSetUserPassword.html
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^5]: https://console.aws.amazon.com/pinpoint/home/
     * [^6]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^7]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ForgotPassword.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#forgotpassword
     *
     * @param array{
     *   ClientId: string,
     *   SecretHash?: null|string,
     *   UserContextData?: null|UserContextDataType|array,
     *   Username: string,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|ForgotPasswordRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws NotAuthorizedException
     * @throws InvalidLambdaResponseException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws CodeDeliveryFailureException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     * @throws ForbiddenException
     */
    public function forgotPassword($input): ForgotPasswordResponse
    {
        $input = ForgotPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ForgotPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new ForgotPasswordResponse($response);
    }

    /**
     * Gets the user attributes and metadata for a user.
     *
     * Authorize this action with a signed-in user's access token. It must include the scope
     * `aws.cognito.signin.user.admin`.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_GetUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#getuser
     *
     * @param array{
     *   AccessToken: string,
     *   '@region'?: string|null,
     * }|GetUserRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws TooManyRequestsException
     * @throws PasswordResetRequiredException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     * @throws InternalErrorException
     * @throws ForbiddenException
     */
    public function getUser($input): GetUserResponse
    {
        $input = GetUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'InternalErrorException' => InternalErrorException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new GetUserResponse($response);
    }

    /**
     * Initiates sign-in for a user in the Amazon Cognito user directory. You can't sign in a user with a federated IdP with
     * `InitiateAuth`. For more information, see Adding user pool sign-in through a third party [^1].
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^2].
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^3]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^4]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^5] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pools-identity-federation.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^3]: https://console.aws.amazon.com/pinpoint/home/
     * [^4]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_InitiateAuth.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#initiateauth
     *
     * @param array{
     *   AuthFlow: AuthFlowType::*,
     *   AuthParameters?: null|array<string, string>,
     *   ClientMetadata?: null|array<string, string>,
     *   ClientId: string,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   '@region'?: string|null,
     * }|InitiateAuthRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws InvalidUserPoolConfigurationException
     * @throws UserLambdaValidationException
     * @throws InvalidLambdaResponseException
     * @throws PasswordResetRequiredException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     * @throws InternalErrorException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws ForbiddenException
     */
    public function initiateAuth($input): InitiateAuthResponse
    {
        $input = InitiateAuthRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'InitiateAuth', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new InitiateAuthResponse($response);
    }

    /**
     * Lists the groups associated with a user pool.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ListGroups.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#listgroups
     *
     * @param array{
     *   UserPoolId: string,
     *   Limit?: null|int,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * }|ListGroupsRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws InternalErrorException
     */
    public function listGroups($input): ListGroupsResponse
    {
        $input = ListGroupsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListGroups', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new ListGroupsResponse($response, $this, $input);
    }

    /**
     * Lists users and their basic details in a user pool.
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^1]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^2]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ListUsers.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#listusers
     *
     * @param array{
     *   UserPoolId: string,
     *   AttributesToGet?: null|string[],
     *   Limit?: null|int,
     *   PaginationToken?: null|string,
     *   Filter?: null|string,
     *   '@region'?: string|null,
     * }|ListUsersRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws NotAuthorizedException
     * @throws InternalErrorException
     */
    public function listUsers($input): ListUsersResponse
    {
        $input = ListUsersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListUsers', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'InternalErrorException' => InternalErrorException::class,
        ]]));

        return new ListUsersResponse($response, $this, $input);
    }

    /**
     * Resends the confirmation (for confirmation of registration) to a specific user in the user pool.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^2]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^3]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^4] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^2]: https://console.aws.amazon.com/pinpoint/home/
     * [^3]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ResendConfirmationCode.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#resendconfirmationcode
     *
     * @param array{
     *   ClientId: string,
     *   SecretHash?: null|string,
     *   UserContextData?: null|UserContextDataType|array,
     *   Username: string,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|ResendConfirmationCodeRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws NotAuthorizedException
     * @throws InvalidLambdaResponseException
     * @throws TooManyRequestsException
     * @throws LimitExceededException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws CodeDeliveryFailureException
     * @throws UserNotFoundException
     * @throws InternalErrorException
     * @throws ForbiddenException
     */
    public function resendConfirmationCode($input): ResendConfirmationCodeResponse
    {
        $input = ResendConfirmationCodeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ResendConfirmationCode', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'InternalErrorException' => InternalErrorException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new ResendConfirmationCodeResponse($response);
    }

    /**
     * Some API operations in a user pool generate a challenge, like a prompt for an MFA code, for device authentication
     * that bypasses MFA, or for a custom authentication challenge. A `RespondToAuthChallenge` API request provides the
     * answer to that challenge, like a code or a secure remote password (SRP). The parameters of a response to an
     * authentication challenge vary with the type of challenge.
     *
     * For more information about custom authentication challenges, see Custom authentication challenge Lambda triggers
     * [^1].
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^2].
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^3]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^4]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^5] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-lambda-challenge.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^3]: https://console.aws.amazon.com/pinpoint/home/
     * [^4]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_RespondToAuthChallenge.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#respondtoauthchallenge
     *
     * @param array{
     *   ClientId: string,
     *   ChallengeName: ChallengeNameType::*,
     *   Session?: null|string,
     *   ChallengeResponses?: null|array<string, string>,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|RespondToAuthChallengeRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws CodeMismatchException
     * @throws ExpiredCodeException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws InvalidPasswordException
     * @throws InvalidLambdaResponseException
     * @throws TooManyRequestsException
     * @throws InvalidUserPoolConfigurationException
     * @throws MFAMethodNotFoundException
     * @throws PasswordResetRequiredException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws AliasExistsException
     * @throws InternalErrorException
     * @throws SoftwareTokenMFANotFoundException
     * @throws ForbiddenException
     */
    public function respondToAuthChallenge($input): RespondToAuthChallengeResponse
    {
        $input = RespondToAuthChallengeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RespondToAuthChallenge', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'CodeMismatchException' => CodeMismatchException::class,
            'ExpiredCodeException' => ExpiredCodeException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'MFAMethodNotFoundException' => MFAMethodNotFoundException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'AliasExistsException' => AliasExistsException::class,
            'InternalErrorException' => InternalErrorException::class,
            'SoftwareTokenMFANotFoundException' => SoftwareTokenMFANotFoundException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new RespondToAuthChallengeResponse($response);
    }

    /**
     * Revokes all of the access tokens generated by, and at the same time as, the specified refresh token. After a token is
     * revoked, you can't use the revoked token to access Amazon Cognito user APIs, or to authorize access to your resource
     * server.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_RevokeToken.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#revoketoken
     *
     * @param array{
     *   Token: string,
     *   ClientId: string,
     *   ClientSecret?: null|string,
     *   '@region'?: string|null,
     * }|RevokeTokenRequest $input
     *
     * @throws TooManyRequestsException
     * @throws InternalErrorException
     * @throws UnauthorizedException
     * @throws InvalidParameterException
     * @throws UnsupportedOperationException
     * @throws UnsupportedTokenTypeException
     * @throws ForbiddenException
     */
    public function revokeToken($input): RevokeTokenResponse
    {
        $input = RevokeTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RevokeToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InternalErrorException' => InternalErrorException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnsupportedOperationException' => UnsupportedOperationException::class,
            'UnsupportedTokenTypeException' => UnsupportedTokenTypeException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new RevokeTokenResponse($response);
    }

    /**
     * Set the user's multi-factor authentication (MFA) method preference, including which MFA factors are activated and if
     * any are preferred. Only one factor can be set as preferred. The preferred MFA factor will be used to authenticate a
     * user if multiple factors are activated. If multiple options are activated and no preference is set, a challenge to
     * choose an MFA option will be returned during sign-in. If an MFA type is activated for a user, the user will be
     * prompted for MFA during all sign-in attempts unless device tracking is turned on and the device has been trusted. If
     * you want MFA to be applied selectively based on the assessed risk level of sign-in attempts, deactivate MFA for users
     * and turn on Adaptive Authentication for the user pool.
     *
     * Authorize this action with a signed-in user's access token. It must include the scope
     * `aws.cognito.signin.user.admin`.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_SetUserMFAPreference.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#setusermfapreference
     *
     * @param array{
     *   SMSMfaSettings?: null|SMSMfaSettingsType|array,
     *   SoftwareTokenMfaSettings?: null|SoftwareTokenMfaSettingsType|array,
     *   AccessToken: string,
     *   '@region'?: string|null,
     * }|SetUserMFAPreferenceRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws PasswordResetRequiredException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     * @throws InternalErrorException
     * @throws ForbiddenException
     */
    public function setUserMfaPreference($input): SetUserMFAPreferenceResponse
    {
        $input = SetUserMFAPreferenceRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SetUserMFAPreference', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'InternalErrorException' => InternalErrorException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new SetUserMFAPreferenceResponse($response);
    }

    /**
     * Registers the user in the specified user pool and creates a user name, password, and user attributes.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^2]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Service, Amazon Simple
     * > Notification Service might place your account in the SMS sandbox. In *sandbox mode [^3]*, you can send messages
     * > only to verified phone numbers. After you test your app while in the sandbox environment, you can move out of the
     * > sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools [^4] in
     * > the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^2]: https://console.aws.amazon.com/pinpoint/home/
     * [^3]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_SignUp.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#signup
     *
     * @param array{
     *   ClientId: string,
     *   SecretHash?: null|string,
     *   Username: string,
     *   Password: string,
     *   UserAttributes?: null|array<AttributeType|array>,
     *   ValidationData?: null|array<AttributeType|array>,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|SignUpRequest $input
     *
     * @throws ResourceNotFoundException
     * @throws InvalidParameterException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws NotAuthorizedException
     * @throws InvalidPasswordException
     * @throws InvalidLambdaResponseException
     * @throws UsernameExistsException
     * @throws TooManyRequestsException
     * @throws InternalErrorException
     * @throws LimitExceededException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws CodeDeliveryFailureException
     * @throws ForbiddenException
     */
    public function signUp($input): SignUpResponse
    {
        $input = SignUpRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SignUp', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'UsernameExistsException' => UsernameExistsException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'InternalErrorException' => InternalErrorException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new SignUpResponse($response);
    }

    /**
     * Use this API to register a user's entered time-based one-time password (TOTP) code and mark the user's software token
     * MFA status as "verified" if successful. The request takes an access token or a session string, but not both.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_VerifySoftwareToken.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-cognito-idp-2016-04-18.html#verifysoftwaretoken
     *
     * @param array{
     *   AccessToken?: null|string,
     *   Session?: null|string,
     *   UserCode: string,
     *   FriendlyDeviceName?: null|string,
     *   '@region'?: string|null,
     * }|VerifySoftwareTokenRequest $input
     *
     * @throws InvalidParameterException
     * @throws ResourceNotFoundException
     * @throws InvalidUserPoolConfigurationException
     * @throws NotAuthorizedException
     * @throws TooManyRequestsException
     * @throws PasswordResetRequiredException
     * @throws UserNotFoundException
     * @throws UserNotConfirmedException
     * @throws InternalErrorException
     * @throws EnableSoftwareTokenMFAException
     * @throws SoftwareTokenMFANotFoundException
     * @throws CodeMismatchException
     * @throws ForbiddenException
     */
    public function verifySoftwareToken($input): VerifySoftwareTokenResponse
    {
        $input = VerifySoftwareTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'VerifySoftwareToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'InternalErrorException' => InternalErrorException::class,
            'EnableSoftwareTokenMFAException' => EnableSoftwareTokenMFAException::class,
            'SoftwareTokenMFANotFoundException' => SoftwareTokenMFANotFoundException::class,
            'CodeMismatchException' => CodeMismatchException::class,
            'ForbiddenException' => ForbiddenException::class,
        ]]));

        return new VerifySoftwareTokenResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
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
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://cognito-idp-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
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
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://cognito-idp-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'cognito-idp',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://cognito-idp.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'cognito-idp',
            'signVersions' => ['v4'],
        ];
    }
}
