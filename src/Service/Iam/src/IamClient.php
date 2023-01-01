<?php

namespace AsyncAws\Iam;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Iam\Exception\ConcurrentModificationException;
use AsyncAws\Iam\Exception\DeleteConflictException;
use AsyncAws\Iam\Exception\EntityAlreadyExistsException;
use AsyncAws\Iam\Exception\EntityTemporarilyUnmodifiableException;
use AsyncAws\Iam\Exception\InvalidInputException;
use AsyncAws\Iam\Exception\LimitExceededException;
use AsyncAws\Iam\Exception\MalformedPolicyDocumentException;
use AsyncAws\Iam\Exception\NoSuchEntityException;
use AsyncAws\Iam\Exception\ServiceFailureException;
use AsyncAws\Iam\Exception\ServiceNotSupportedException;
use AsyncAws\Iam\Input\AddUserToGroupRequest;
use AsyncAws\Iam\Input\CreateAccessKeyRequest;
use AsyncAws\Iam\Input\CreateServiceSpecificCredentialRequest;
use AsyncAws\Iam\Input\CreateUserRequest;
use AsyncAws\Iam\Input\DeleteAccessKeyRequest;
use AsyncAws\Iam\Input\DeleteServiceSpecificCredentialRequest;
use AsyncAws\Iam\Input\DeleteUserPolicyRequest;
use AsyncAws\Iam\Input\DeleteUserRequest;
use AsyncAws\Iam\Input\GetUserRequest;
use AsyncAws\Iam\Input\ListServiceSpecificCredentialsRequest;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Input\PutUserPolicyRequest;
use AsyncAws\Iam\Input\UpdateUserRequest;
use AsyncAws\Iam\Result\CreateAccessKeyResponse;
use AsyncAws\Iam\Result\CreateServiceSpecificCredentialResponse;
use AsyncAws\Iam\Result\CreateUserResponse;
use AsyncAws\Iam\Result\GetUserResponse;
use AsyncAws\Iam\Result\ListServiceSpecificCredentialsResponse;
use AsyncAws\Iam\Result\ListUsersResponse;
use AsyncAws\Iam\ValueObject\Tag;
use AsyncAws\Iam\ValueObject\User;

class IamClient extends AbstractApi
{
    /**
     * Adds the specified user to the specified group.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_AddUserToGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#addusertogroup
     *
     * @param array{
     *   GroupName: string,
     *   UserName: string,
     *   @region?: string,
     * }|AddUserToGroupRequest $input
     *
     * @throws NoSuchEntityException
     * @throws LimitExceededException
     * @throws ServiceFailureException
     */
    public function addUserToGroup($input): Result
    {
        $input = AddUserToGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddUserToGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
            'LimitExceeded' => LimitExceededException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Creates a new Amazon Web Services secret access key and corresponding Amazon Web Services access key ID for the
     * specified user. The default status for new keys is `Active`.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateAccessKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#createaccesskey
     *
     * @param array{
     *   UserName?: string,
     *   @region?: string,
     * }|CreateAccessKeyRequest $input
     *
     * @throws NoSuchEntityException
     * @throws LimitExceededException
     * @throws ServiceFailureException
     */
    public function createAccessKey($input = []): CreateAccessKeyResponse
    {
        $input = CreateAccessKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateAccessKey', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
            'LimitExceeded' => LimitExceededException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new CreateAccessKeyResponse($response);
    }

    /**
     * Generates a set of credentials consisting of a user name and password that can be used to access the service
     * specified in the request. These credentials are generated by IAM, and can be used only for the specified service.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateServiceSpecificCredential.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#createservicespecificcredential
     *
     * @param array{
     *   UserName: string,
     *   ServiceName: string,
     *   @region?: string,
     * }|CreateServiceSpecificCredentialRequest $input
     *
     * @throws LimitExceededException
     * @throws NoSuchEntityException
     * @throws ServiceNotSupportedException
     */
    public function createServiceSpecificCredential($input): CreateServiceSpecificCredentialResponse
    {
        $input = CreateServiceSpecificCredentialRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateServiceSpecificCredential', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceeded' => LimitExceededException::class,
            'NoSuchEntity' => NoSuchEntityException::class,
            'NotSupportedService' => ServiceNotSupportedException::class,
        ]]));

        return new CreateServiceSpecificCredentialResponse($response);
    }

    /**
     * Creates a new IAM user for your Amazon Web Services account.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#createuser
     *
     * @param array{
     *   Path?: string,
     *   UserName: string,
     *   PermissionsBoundary?: string,
     *   Tags?: Tag[],
     *   @region?: string,
     * }|CreateUserRequest $input
     *
     * @throws LimitExceededException
     * @throws EntityAlreadyExistsException
     * @throws NoSuchEntityException
     * @throws InvalidInputException
     * @throws ConcurrentModificationException
     * @throws ServiceFailureException
     */
    public function createUser($input): CreateUserResponse
    {
        $input = CreateUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceeded' => LimitExceededException::class,
            'EntityAlreadyExists' => EntityAlreadyExistsException::class,
            'NoSuchEntity' => NoSuchEntityException::class,
            'InvalidInput' => InvalidInputException::class,
            'ConcurrentModification' => ConcurrentModificationException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new CreateUserResponse($response);
    }

    /**
     * Deletes the access key pair associated with the specified IAM user.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_DeleteAccessKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#deleteaccesskey
     *
     * @param array{
     *   UserName?: string,
     *   AccessKeyId: string,
     *   @region?: string,
     * }|DeleteAccessKeyRequest $input
     *
     * @throws NoSuchEntityException
     * @throws LimitExceededException
     * @throws ServiceFailureException
     */
    public function deleteAccessKey($input): Result
    {
        $input = DeleteAccessKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteAccessKey', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
            'LimitExceeded' => LimitExceededException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deletes the specified service-specific credential.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_DeleteServiceSpecificCredential.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#deleteservicespecificcredential
     *
     * @param array{
     *   UserName?: string,
     *   ServiceSpecificCredentialId: string,
     *   @region?: string,
     * }|DeleteServiceSpecificCredentialRequest $input
     *
     * @throws NoSuchEntityException
     */
    public function deleteServiceSpecificCredential($input): Result
    {
        $input = DeleteServiceSpecificCredentialRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteServiceSpecificCredential', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deletes the specified IAM user. Unlike the Amazon Web Services Management Console, when you delete a user
     * programmatically, you must delete the items attached to the user manually, or the deletion fails. For more
     * information, see Deleting an IAM user. Before attempting to delete a user, remove the following items:.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_users_manage.html#id_users_deleting_cli
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_DeleteUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#deleteuser
     *
     * @param array{
     *   UserName: string,
     *   @region?: string,
     * }|DeleteUserRequest $input
     *
     * @throws LimitExceededException
     * @throws NoSuchEntityException
     * @throws DeleteConflictException
     * @throws ConcurrentModificationException
     * @throws ServiceFailureException
     */
    public function deleteUser($input): Result
    {
        $input = DeleteUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceeded' => LimitExceededException::class,
            'NoSuchEntity' => NoSuchEntityException::class,
            'DeleteConflict' => DeleteConflictException::class,
            'ConcurrentModification' => ConcurrentModificationException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deletes the specified inline policy that is embedded in the specified IAM user.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_DeleteUserPolicy.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#deleteuserpolicy
     *
     * @param array{
     *   UserName: string,
     *   PolicyName: string,
     *   @region?: string,
     * }|DeleteUserPolicyRequest $input
     *
     * @throws NoSuchEntityException
     * @throws LimitExceededException
     * @throws ServiceFailureException
     */
    public function deleteUserPolicy($input): Result
    {
        $input = DeleteUserPolicyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteUserPolicy', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
            'LimitExceeded' => LimitExceededException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Retrieves information about the specified IAM user, including the user's creation date, path, unique ID, and ARN.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_GetUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#getuser
     *
     * @param array{
     *   UserName?: string,
     *   @region?: string,
     * }|GetUserRequest $input
     *
     * @throws NoSuchEntityException
     * @throws ServiceFailureException
     */
    public function getUser($input = []): GetUserResponse
    {
        $input = GetUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new GetUserResponse($response);
    }

    /**
     * Returns information about the service-specific credentials associated with the specified IAM user. If none exists,
     * the operation returns an empty list. The service-specific credentials returned by this operation are used only for
     * authenticating the IAM user to a specific service. For more information about using service-specific credentials to
     * authenticate to an Amazon Web Services service, see Set up service-specific credentials in the CodeCommit User Guide.
     *
     * @see https://docs.aws.amazon.com/codecommit/latest/userguide/setting-up-gc.html
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListServiceSpecificCredentials.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#listservicespecificcredentials
     *
     * @param array{
     *   UserName?: string,
     *   ServiceName?: string,
     *   @region?: string,
     * }|ListServiceSpecificCredentialsRequest $input
     *
     * @throws NoSuchEntityException
     * @throws ServiceNotSupportedException
     */
    public function listServiceSpecificCredentials($input = []): ListServiceSpecificCredentialsResponse
    {
        $input = ListServiceSpecificCredentialsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListServiceSpecificCredentials', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
            'NotSupportedService' => ServiceNotSupportedException::class,
        ]]));

        return new ListServiceSpecificCredentialsResponse($response);
    }

    /**
     * Lists the IAM users that have the specified path prefix. If no path prefix is specified, the operation returns all
     * users in the Amazon Web Services account. If there are none, the operation returns an empty list.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_ListUsers.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#listusers
     *
     * @param array{
     *   PathPrefix?: string,
     *   Marker?: string,
     *   MaxItems?: int,
     *   @region?: string,
     * }|ListUsersRequest $input
     *
     * @throws ServiceFailureException
     */
    public function listUsers($input = []): ListUsersResponse
    {
        $input = ListUsersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListUsers', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new ListUsersResponse($response, $this, $input);
    }

    /**
     * Adds or updates an inline policy document that is embedded in the specified IAM user.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_PutUserPolicy.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#putuserpolicy
     *
     * @param array{
     *   UserName: string,
     *   PolicyName: string,
     *   PolicyDocument: string,
     *   @region?: string,
     * }|PutUserPolicyRequest $input
     *
     * @throws LimitExceededException
     * @throws MalformedPolicyDocumentException
     * @throws NoSuchEntityException
     * @throws ServiceFailureException
     */
    public function putUserPolicy($input): Result
    {
        $input = PutUserPolicyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutUserPolicy', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'LimitExceeded' => LimitExceededException::class,
            'MalformedPolicyDocument' => MalformedPolicyDocumentException::class,
            'NoSuchEntity' => NoSuchEntityException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Updates the name and/or the path of the specified IAM user.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_UpdateUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#updateuser
     *
     * @param array{
     *   UserName: string,
     *   NewPath?: string,
     *   NewUserName?: string,
     *   @region?: string,
     * }|UpdateUserRequest $input
     *
     * @throws NoSuchEntityException
     * @throws LimitExceededException
     * @throws EntityAlreadyExistsException
     * @throws EntityTemporarilyUnmodifiableException
     * @throws ConcurrentModificationException
     * @throws ServiceFailureException
     */
    public function updateUser($input): Result
    {
        $input = UpdateUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateUser', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'NoSuchEntity' => NoSuchEntityException::class,
            'LimitExceeded' => LimitExceededException::class,
            'EntityAlreadyExists' => EntityAlreadyExistsException::class,
            'EntityTemporarilyUnmodifiable' => EntityTemporarilyUnmodifiableException::class,
            'ConcurrentModification' => ConcurrentModificationException::class,
            'ServiceFailure' => ServiceFailureException::class,
        ]]));

        return new Result($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            return [
                'endpoint' => 'https://iam.amazonaws.com',
                'signRegion' => 'us-east-1',
                'signService' => 'iam',
                'signVersions' => ['v4'],
            ];
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://iam.cn-north-1.amazonaws.com.cn',
                    'signRegion' => 'cn-north-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'iam':
                return [
                    'endpoint' => 'https://iam.iam.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
            case 'aws-us-gov-global-fips':
            case 'iam-govcloud-fips':
                return [
                    'endpoint' => 'https://iam.us-gov.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'iam-govcloud':
                return [
                    'endpoint' => 'https://iam.iam-govcloud.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'aws-global-fips':
            case 'iam-fips':
                return [
                    'endpoint' => 'https://iam-fips.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => 'https://iam.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://iam.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => 'https://iam.amazonaws.com',
            'signRegion' => 'us-east-1',
            'signService' => 'iam',
            'signVersions' => ['v4'],
        ];
    }
}
