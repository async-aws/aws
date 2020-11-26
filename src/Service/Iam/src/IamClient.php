<?php

namespace AsyncAws\Iam;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Iam\Input\AddUserToGroupRequest;
use AsyncAws\Iam\Input\CreateAccessKeyRequest;
use AsyncAws\Iam\Input\CreateUserRequest;
use AsyncAws\Iam\Input\DeleteAccessKeyRequest;
use AsyncAws\Iam\Input\DeleteUserRequest;
use AsyncAws\Iam\Input\GetUserRequest;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Input\UpdateUserRequest;
use AsyncAws\Iam\Result\CreateAccessKeyResponse;
use AsyncAws\Iam\Result\CreateUserResponse;
use AsyncAws\Iam\Result\GetUserResponse;
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
     */
    public function addUserToGroup($input): Result
    {
        $input = AddUserToGroupRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AddUserToGroup', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Creates a new AWS secret access key and corresponding AWS access key ID for the specified user. The default status
     * for new keys is `Active`.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_CreateAccessKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#createaccesskey
     *
     * @param array{
     *   UserName?: string,
     *   @region?: string,
     * }|CreateAccessKeyRequest $input
     */
    public function createAccessKey($input = []): CreateAccessKeyResponse
    {
        $input = CreateAccessKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateAccessKey', 'region' => $input->getRegion()]));

        return new CreateAccessKeyResponse($response);
    }

    /**
     * Creates a new IAM user for your AWS account.
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
     */
    public function createUser($input): CreateUserResponse
    {
        $input = CreateUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateUser', 'region' => $input->getRegion()]));

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
     */
    public function deleteAccessKey($input): Result
    {
        $input = DeleteAccessKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteAccessKey', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Deletes the specified IAM user. Unlike the AWS Management Console, when you delete a user programmatically, you must
     * delete the items attached to the user manually, or the deletion fails. For more information, see Deleting an IAM
     * User. Before attempting to delete a user, remove the following items:.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_users_manage.html#id_users_deleting_cli
     * @see https://docs.aws.amazon.com/IAM/latest/APIReference/API_DeleteUser.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#deleteuser
     *
     * @param array{
     *   UserName: string,
     *   @region?: string,
     * }|DeleteUserRequest $input
     */
    public function deleteUser($input): Result
    {
        $input = DeleteUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteUser', 'region' => $input->getRegion()]));

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
     */
    public function getUser($input = []): GetUserResponse
    {
        $input = GetUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetUser', 'region' => $input->getRegion()]));

        return new GetUserResponse($response);
    }

    /**
     * Lists the IAM users that have the specified path prefix. If no path prefix is specified, the operation returns all
     * users in the AWS account. If there are none, the operation returns an empty list.
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
     */
    public function listUsers($input = []): ListUsersResponse
    {
        $input = ListUsersRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListUsers', 'region' => $input->getRegion()]));

        return new ListUsersResponse($response, $this, $input);
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
     */
    public function updateUser($input): Result
    {
        $input = UpdateUserRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateUser', 'region' => $input->getRegion()]));

        return new Result($response);
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
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://iam.us-gov.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
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
            case 'iam-fips':
                return [
                    'endpoint' => 'https://iam-fips.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'iam',
                    'signVersions' => ['v4'],
                ];
            case 'iam-govcloud-fips':
                return [
                    'endpoint' => 'https://iam.us-gov.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
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
