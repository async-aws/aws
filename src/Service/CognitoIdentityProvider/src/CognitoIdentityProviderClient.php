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
use AsyncAws\CognitoIdentityProvider\Exception\PasswordHistoryPolicyViolationException;
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
use AsyncAws\CognitoIdentityProvider\ValueObject\EmailMfaSettingsType;
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminAddUserToGroup($input): Result
    {
        $input = AdminAddUserToGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminAddUserToGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Confirms user sign-up as an administrator.
     *
     * This request sets a user account active in a user pool that requires confirmation of new user accounts [^1] before
     * they can sign in. You can configure your user pool to not send confirmation codes to new users and instead confirm
     * them with this API operation on the back end.
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
     * To configure your user pool to require administrative confirmation of users, set `AllowAdminCreateUserOnly` to `true`
     * in a `CreateUserPool` or `UpdateUserPool` request.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/signing-up-users-in-your-app.html#signing-up-users-in-your-app-and-confirming-them-as-admin
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
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
     * @throws InternalErrorException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyFailedAttemptsException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotFoundException
     */
    public function adminConfirmSignUp($input): AdminConfirmSignUpResponse
    {
        $input = AdminConfirmSignUpRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminConfirmSignUp', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyFailedAttemptsException' => TooManyFailedAttemptsException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminConfirmSignUpResponse($response);
    }

    /**
     * Creates a new user in the specified user pool.
     *
     * If `MessageAction` isn't set, the default is to send a welcome message via email or phone (SMS).
     *
     * This message is based on a template that you configured in your call to create or update a user pool. This template
     * includes your custom sign-up instructions and placeholders for user name and temporary password.
     *
     * Alternatively, you can call `AdminCreateUser` with `SUPPRESS` for the `MessageAction` parameter, and Amazon Cognito
     * won't send any email.
     *
     * In either case, if the user has a password, they will be in the `FORCE_CHANGE_PASSWORD` state until they sign in and
     * set their password. Your invitation message template must have the `{####}` password placeholder if your users have
     * passwords. If your template doesn't have this placeholder, Amazon Cognito doesn't deliver the invitation message. In
     * this case, you must update your message template and resend the password with a new `AdminCreateUser` request with a
     * `MessageAction` value of `RESEND`.
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^1]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^2]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^3] in the *Amazon Cognito Developer Guide*.
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
     * @throws CodeDeliveryFailureException
     * @throws InternalErrorException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws NotAuthorizedException
     * @throws PreconditionNotMetException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UnsupportedUserStateException
     * @throws UserLambdaValidationException
     * @throws UserNotFoundException
     * @throws UsernameExistsException
     */
    public function adminCreateUser($input): AdminCreateUserResponse
    {
        $input = AdminCreateUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminCreateUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PreconditionNotMetException' => PreconditionNotMetException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UnsupportedUserStateException' => UnsupportedUserStateException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotFoundException' => UserNotFoundException::class,
            'UsernameExistsException' => UsernameExistsException::class,
        ]]));

        return new AdminCreateUserResponse($response);
    }

    /**
     * Deletes a user profile in your user pool.
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminDeleteUser($input): Result
    {
        $input = AdminDeleteUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminDeleteUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deactivates a user profile and revokes all access tokens for the user. A deactivated user can't sign in, but still
     * appears in the responses to `ListUsers` API requests.
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminDisableUser($input): AdminDisableUserResponse
    {
        $input = AdminDisableUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminDisableUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminDisableUserResponse($response);
    }

    /**
     * Activates sign-in for a user profile that previously had sign-in access disabled.
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminEnableUser($input): AdminEnableUserResponse
    {
        $input = AdminEnableUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminEnableUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminEnableUserResponse($response);
    }

    /**
     * Given a username, returns details about a user profile in a user pool. You can specify alias attributes in the
     * `Username` request parameter.
     *
     * This operation contributes to your monthly active user (MAU) count for the purpose of billing.
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminGetUser($input): AdminGetUserResponse
    {
        $input = AdminGetUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminGetUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminGetUserResponse($response);
    }

    /**
     * Starts sign-in for applications with a server-side component, for example a traditional web application. This
     * operation specifies the authentication flow that you'd like to begin. The authentication flow that you specify must
     * be supported in your app client configuration. For more information about authentication flows, see Authentication
     * flows [^1].
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^2]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^3]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^4] in the *Amazon Cognito Developer Guide*.
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
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-authentication-flow-methods.html
     * [^2]: https://console.aws.amazon.com/pinpoint/home/
     * [^3]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
     * [^5]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^6]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
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
     *   Session?: null|string,
     *   '@region'?: string|null,
     * }|AdminInitiateAuthRequest $input
     *
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws InvalidUserPoolConfigurationException
     * @throws MFAMethodNotFoundException
     * @throws NotAuthorizedException
     * @throws PasswordResetRequiredException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UnsupportedOperationException
     * @throws UserLambdaValidationException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function adminInitiateAuth($input): AdminInitiateAuthResponse
    {
        $input = AdminInitiateAuthRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminInitiateAuth', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'MFAMethodNotFoundException' => MFAMethodNotFoundException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UnsupportedOperationException' => UnsupportedOperationException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminInitiateAuthResponse($response);
    }

    /**
     * Given a username and a group name, removes them from the group. User pool groups are identifiers that you can
     * reference from the contents of ID and access tokens, and set preferred IAM roles for identity-pool authentication.
     * For more information, see Adding groups to a user pool [^1].
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
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pools-user-groups.html
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminRemoveUserFromGroup($input): Result
    {
        $input = AdminRemoveUserFromGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminRemoveUserFromGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Resets the specified user's password in a user pool. This operation doesn't change the user's password, but sends a
     * password-reset code.
     *
     * To use this API operation, your user pool must have self-service account recovery configured.
     *
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^1]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^2]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^3] in the *Amazon Cognito Developer Guide*.
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
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotFoundException
     */
    public function adminResetUserPassword($input): AdminResetUserPasswordResponse
    {
        $input = AdminResetUserPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminResetUserPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminResetUserPasswordResponse($response);
    }

    /**
     * Sets the specified user's password in a user pool. This operation administratively sets a temporary or permanent
     * password for a user. With this operation, you can bypass self-service password changes and permit immediate sign-in
     * with the password that you set. To do this, set `Permanent` to `true`.
     *
     * You can also set a new temporary password in this request, send it to a user, and require them to choose a new
     * password on their next sign-in. To do this, set `Permanent` to `false`.
     *
     * If the password is temporary, the user's `Status` becomes `FORCE_CHANGE_PASSWORD`. When the user next tries to sign
     * in, the `InitiateAuth` or `AdminInitiateAuth` response includes the `NEW_PASSWORD_REQUIRED` challenge. If the user
     * doesn't sign in before the temporary password expires, they can no longer sign in and you must repeat this operation
     * to set a temporary or permanent password for them.
     *
     * After the user sets a new password, or if you set a permanent password, their status becomes `Confirmed`.
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws NotAuthorizedException
     * @throws PasswordHistoryPolicyViolationException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminSetUserPassword($input): AdminSetUserPasswordResponse
    {
        $input = AdminSetUserPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminSetUserPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordHistoryPolicyViolationException' => PasswordHistoryPolicyViolationException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminSetUserPasswordResponse($response);
    }

    /**
     * Updates the specified user's attributes. To delete an attribute from your user, submit the attribute in your API
     * request with a blank value.
     *
     * For custom attributes, you must add a `custom:` prefix to the attribute name, for example `custom:department`.
     *
     * This operation can set a user's email address or phone number as verified and permit immediate sign-in in user pools
     * that require verification of these attributes. To do this, set the `email_verified` or `phone_number_verified`
     * attribute to `true`.
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
     * > This action might generate an SMS text message. Starting June 1, 2021, US telecom carriers require you to register
     * > an origination phone number before you can send SMS messages to US phone numbers. If you use SMS text messages in
     * > Amazon Cognito, you must register a phone number with Amazon Pinpoint [^3]. Amazon Cognito uses the registered
     * > number automatically. Otherwise, Amazon Cognito users who must receive SMS messages might not be able to sign up,
     * > activate their accounts, or sign in.
     * >
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^4]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^5] in the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^3]: https://console.aws.amazon.com/pinpoint/home/
     * [^4]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
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
     * @throws AliasExistsException
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotFoundException
     */
    public function adminUpdateUserAttributes($input): AdminUpdateUserAttributesResponse
    {
        $input = AdminUpdateUserAttributesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminUpdateUserAttributes', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AliasExistsException' => AliasExistsException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotFoundException' => UserNotFoundException::class,
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
     * Other requests might be valid until your user's token expires. This operation doesn't clear the managed login [^4]
     * session cookie. To clear the session for a user who signed in with managed login or the classic hosted UI, direct
     * their browser session to the logout endpoint [^5].
     *
     * > Amazon Cognito evaluates Identity and Access Management (IAM) policies in requests for this API operation. For this
     * > operation, you must use IAM credentials to authorize requests, and you must grant yourself the corresponding IAM
     * > permission in a policy.
     * >
     * > **Learn more**
     * >
     * > - Signing Amazon Web Services API Requests [^6]
     * > - Using the Amazon Cognito user pools API and user pool endpoints [^7]
     * >
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^2]: https://docs.aws.amazon.com/cognitoidentity/latest/APIReference/API_GetId.html
     * [^3]: https://docs.aws.amazon.com/cognitoidentity/latest/APIReference/API_CognitoIdentityProvider.html
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pools-managed-login.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/logout-endpoint.html
     * [^6]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^7]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotFoundException
     */
    public function adminUserGlobalSignOut($input): AdminUserGlobalSignOutResponse
    {
        $input = AdminUserGlobalSignOutRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AdminUserGlobalSignOut', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new AdminUserGlobalSignOutResponse($response);
    }

    /**
     * Begins setup of time-based one-time password (TOTP) multi-factor authentication (MFA) for a user, with a unique
     * private key that Amazon Cognito generates and returns in the API response. You can authorize an
     * `AssociateSoftwareToken` request with either the user's access token, or a session string from a challenge response
     * that you received from Amazon Cognito.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * Authorize this action with a signed-in user's access token. It must include the scope
     * `aws.cognito.signin.user.admin`.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
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
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws SoftwareTokenMFANotFoundException
     */
    public function associateSoftwareToken($input = []): AssociateSoftwareTokenResponse
    {
        $input = AssociateSoftwareTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AssociateSoftwareToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'SoftwareTokenMFANotFoundException' => SoftwareTokenMFANotFoundException::class,
        ]]));

        return new AssociateSoftwareTokenResponse($response);
    }

    /**
     * Changes the password for the currently signed-in user.
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
     *   PreviousPassword?: null|string,
     *   ProposedPassword: string,
     *   AccessToken: string,
     *   '@region'?: string|null,
     * }|ChangePasswordRequest $input
     *
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws PasswordHistoryPolicyViolationException
     * @throws PasswordResetRequiredException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function changePassword($input): ChangePasswordResponse
    {
        $input = ChangePasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ChangePassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordHistoryPolicyViolationException' => PasswordHistoryPolicyViolationException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new ChangePasswordResponse($response);
    }

    /**
     * This public API operation accepts a confirmation code that Amazon Cognito sent to a user and accepts a new password
     * for that user.
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
     * @throws CodeMismatchException
     * @throws ExpiredCodeException
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws PasswordHistoryPolicyViolationException
     * @throws ResourceNotFoundException
     * @throws TooManyFailedAttemptsException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function confirmForgotPassword($input): ConfirmForgotPasswordResponse
    {
        $input = ConfirmForgotPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ConfirmForgotPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeMismatchException' => CodeMismatchException::class,
            'ExpiredCodeException' => ExpiredCodeException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordHistoryPolicyViolationException' => PasswordHistoryPolicyViolationException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyFailedAttemptsException' => TooManyFailedAttemptsException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new ConfirmForgotPasswordResponse($response);
    }

    /**
     * Confirms the account of a new user. This public API operation submits a code that Amazon Cognito sent to your user
     * when they signed up in your user pool. After your user enters their code, they confirm ownership of the email address
     * or phone number that they provided, and their user account becomes active. Depending on your user pool configuration,
     * your users will receive their confirmation code in an email or SMS message.
     *
     * Local users who signed up in your user pool are the only type of user who can confirm sign-up with a code. Users who
     * federate through an external identity provider (IdP) have already been confirmed by their IdP.
     *
     * > Amazon Cognito doesn't evaluate Identity and Access Management (IAM) policies in requests for this API operation.
     * > For this operation, you can't use IAM credentials to authorize requests, and you can't grant IAM permissions in
     * > policies. For more information about authorization models in Amazon Cognito, see Using the Amazon Cognito user
     * > pools API and user pool endpoints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
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
     *   Session?: null|string,
     *   '@region'?: string|null,
     * }|ConfirmSignUpRequest $input
     *
     * @throws AliasExistsException
     * @throws CodeMismatchException
     * @throws ExpiredCodeException
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyFailedAttemptsException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotFoundException
     */
    public function confirmSignUp($input): ConfirmSignUpResponse
    {
        $input = ConfirmSignUpRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ConfirmSignUp', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AliasExistsException' => AliasExistsException::class,
            'CodeMismatchException' => CodeMismatchException::class,
            'ExpiredCodeException' => ExpiredCodeException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyFailedAttemptsException' => TooManyFailedAttemptsException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new ConfirmSignUpResponse($response);
    }

    /**
     * Creates a new group in the specified user pool. For more information about user pool groups, see Adding groups to a
     * user pool [^1].
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
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pools-user-groups.html
     * [^2]: https://docs.aws.amazon.com/IAM/latest/UserGuide/reference_aws-signing.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
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
     * @throws GroupExistsException
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     */
    public function createGroup($input): CreateGroupResponse
    {
        $input = CreateGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'GroupExistsException' => GroupExistsException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new CreateGroupResponse($response);
    }

    /**
     * Sends a password-reset confirmation code for the currently signed-in user.
     *
     * For the `Username` parameter, you can use the username or user alias.
     *
     * If neither a verified phone number nor a verified email exists, Amazon Cognito responds with an
     * `InvalidParameterException` error . If your app client has a client secret and you don't provide a `SECRET_HASH`
     * parameter, this API returns `NotAuthorizedException`.
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
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^3]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^4] in the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pools-API-operations.html
     * [^2]: https://console.aws.amazon.com/pinpoint/home/
     * [^3]: https://docs.aws.amazon.com/sns/latest/dg/sns-sms-sandbox.html
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-sms-settings.html
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
     * @throws CodeDeliveryFailureException
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotFoundException
     */
    public function forgotPassword($input): ForgotPasswordResponse
    {
        $input = ForgotPasswordRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ForgotPassword', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new ForgotPasswordResponse($response);
    }

    /**
     * Gets user attributes and and MFA settings for the currently signed-in user.
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
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws PasswordResetRequiredException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function getUser($input): GetUserResponse
    {
        $input = GetUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new GetUserResponse($response);
    }

    /**
     * Declares an authentication flow and initiates sign-in for a user in the Amazon Cognito user directory. Amazon Cognito
     * might respond with an additional challenge or an `AuthenticationResult` that contains the outcome of a successful
     * authentication. You can't sign in a user with a federated IdP with `InitiateAuth`. For more information, see
     * Authentication [^1].
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
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^4]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^5] in the *Amazon Cognito Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/authentication.html
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
     *   Session?: null|string,
     *   '@region'?: string|null,
     * }|InitiateAuthRequest $input
     *
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws InvalidUserPoolConfigurationException
     * @throws NotAuthorizedException
     * @throws PasswordResetRequiredException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UnsupportedOperationException
     * @throws UserLambdaValidationException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function initiateAuth($input): InitiateAuthResponse
    {
        $input = InitiateAuthRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'InitiateAuth', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UnsupportedOperationException' => UnsupportedOperationException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new InitiateAuthResponse($response);
    }

    /**
     * Given a user pool ID, returns user pool groups and their details.
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     */
    public function listGroups($input): ListGroupsResponse
    {
        $input = ListGroupsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListGroups', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListGroupsResponse($response, $this, $input);
    }

    /**
     * Given a user pool ID, returns a list of users and their basic details in a user pool.
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
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     */
    public function listUsers($input): ListUsersResponse
    {
        $input = ListUsersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListUsers', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new ListUsersResponse($response, $this, $input);
    }

    /**
     * Resends the code that confirms a new account for a user who has signed up in your user pool. Amazon Cognito sends
     * confirmation codes to the user attribute in the `AutoVerifiedAttributes` property of your user pool. When you prompt
     * new users for the confirmation code, include a "Resend code" option that generates a call to this API operation.
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
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^3]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^4] in the *Amazon Cognito Developer Guide*.
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
     * @throws CodeDeliveryFailureException
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotFoundException
     */
    public function resendConfirmationCode($input): ResendConfirmationCodeResponse
    {
        $input = ResendConfirmationCodeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ResendConfirmationCode', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotFoundException' => UserNotFoundException::class,
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
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^4]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^5] in the *Amazon Cognito Developer Guide*.
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
     * @throws AliasExistsException
     * @throws CodeMismatchException
     * @throws ExpiredCodeException
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws InvalidUserPoolConfigurationException
     * @throws MFAMethodNotFoundException
     * @throws NotAuthorizedException
     * @throws PasswordHistoryPolicyViolationException
     * @throws PasswordResetRequiredException
     * @throws ResourceNotFoundException
     * @throws SoftwareTokenMFANotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function respondToAuthChallenge($input): RespondToAuthChallengeResponse
    {
        $input = RespondToAuthChallengeRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RespondToAuthChallenge', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AliasExistsException' => AliasExistsException::class,
            'CodeMismatchException' => CodeMismatchException::class,
            'ExpiredCodeException' => ExpiredCodeException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'MFAMethodNotFoundException' => MFAMethodNotFoundException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordHistoryPolicyViolationException' => PasswordHistoryPolicyViolationException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'SoftwareTokenMFANotFoundException' => SoftwareTokenMFANotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
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
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     * @throws UnsupportedOperationException
     * @throws UnsupportedTokenTypeException
     */
    public function revokeToken($input): RevokeTokenResponse
    {
        $input = RevokeTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RevokeToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'UnsupportedOperationException' => UnsupportedOperationException::class,
            'UnsupportedTokenTypeException' => UnsupportedTokenTypeException::class,
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
     *   EmailMfaSettings?: null|EmailMfaSettingsType|array,
     *   AccessToken: string,
     *   '@region'?: string|null,
     * }|SetUserMFAPreferenceRequest $input
     *
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws NotAuthorizedException
     * @throws PasswordResetRequiredException
     * @throws ResourceNotFoundException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function setUserMfaPreference($input): SetUserMFAPreferenceResponse
    {
        $input = SetUserMFAPreferenceRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SetUserMFAPreference', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
        ]]));

        return new SetUserMFAPreferenceResponse($response);
    }

    /**
     * Registers a user with an app client and requests a user name, password, and user attributes in the user pool.
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
     * > If you have never used SMS text messages with Amazon Cognito or any other Amazon Web Services service, Amazon
     * > Simple Notification Service might place your account in the SMS sandbox. In *sandbox mode [^3]*, you can send
     * > messages only to verified phone numbers. After you test your app while in the sandbox environment, you can move out
     * > of the sandbox and into production. For more information, see SMS message settings for Amazon Cognito user pools
     * > [^4] in the *Amazon Cognito Developer Guide*.
     *
     * You might receive a `LimitExceeded` exception in response to this request if you have exceeded a rate quota for email
     * or SMS messages, and if your user pool automatically verifies email addresses or phone numbers. When you get this
     * exception in the response, the user is successfully created and is in an `UNCONFIRMED` state.
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
     *   Password?: null|string,
     *   UserAttributes?: null|array<AttributeType|array>,
     *   ValidationData?: null|array<AttributeType|array>,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|SignUpRequest $input
     *
     * @throws CodeDeliveryFailureException
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidEmailRoleAccessPolicyException
     * @throws InvalidLambdaResponseException
     * @throws InvalidParameterException
     * @throws InvalidPasswordException
     * @throws InvalidSmsRoleAccessPolicyException
     * @throws InvalidSmsRoleTrustRelationshipException
     * @throws LimitExceededException
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnexpectedLambdaException
     * @throws UserLambdaValidationException
     * @throws UsernameExistsException
     */
    public function signUp($input): SignUpResponse
    {
        $input = SignUpRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'SignUp', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeDeliveryFailureException' => CodeDeliveryFailureException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidEmailRoleAccessPolicyException' => InvalidEmailRoleAccessPolicyException::class,
            'InvalidLambdaResponseException' => InvalidLambdaResponseException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidPasswordException' => InvalidPasswordException::class,
            'InvalidSmsRoleAccessPolicyException' => InvalidSmsRoleAccessPolicyException::class,
            'InvalidSmsRoleTrustRelationshipException' => InvalidSmsRoleTrustRelationshipException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnexpectedLambdaException' => UnexpectedLambdaException::class,
            'UserLambdaValidationException' => UserLambdaValidationException::class,
            'UsernameExistsException' => UsernameExistsException::class,
        ]]));

        return new SignUpResponse($response);
    }

    /**
     * Registers the current user's time-based one-time password (TOTP) authenticator with a code generated in their
     * authenticator app from a private key that's supplied by your user pool. Marks the user's software token MFA status as
     * "verified" if successful. The request takes an access token or a session string, but not both.
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
     * @throws CodeMismatchException
     * @throws EnableSoftwareTokenMFAException
     * @throws ForbiddenException
     * @throws InternalErrorException
     * @throws InvalidParameterException
     * @throws InvalidUserPoolConfigurationException
     * @throws NotAuthorizedException
     * @throws PasswordResetRequiredException
     * @throws ResourceNotFoundException
     * @throws SoftwareTokenMFANotFoundException
     * @throws TooManyRequestsException
     * @throws UserNotConfirmedException
     * @throws UserNotFoundException
     */
    public function verifySoftwareToken($input): VerifySoftwareTokenResponse
    {
        $input = VerifySoftwareTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'VerifySoftwareToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'CodeMismatchException' => CodeMismatchException::class,
            'EnableSoftwareTokenMFAException' => EnableSoftwareTokenMFAException::class,
            'ForbiddenException' => ForbiddenException::class,
            'InternalErrorException' => InternalErrorException::class,
            'InvalidParameterException' => InvalidParameterException::class,
            'InvalidUserPoolConfigurationException' => InvalidUserPoolConfigurationException::class,
            'NotAuthorizedException' => NotAuthorizedException::class,
            'PasswordResetRequiredException' => PasswordResetRequiredException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'SoftwareTokenMFANotFoundException' => SoftwareTokenMFANotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UserNotConfirmedException' => UserNotConfirmedException::class,
            'UserNotFoundException' => UserNotFoundException::class,
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
