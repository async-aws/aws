<?php

namespace AsyncAws\Iam;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Iam\Input\CreateUserRequest;
use AsyncAws\Iam\Input\DeleteUserRequest;
use AsyncAws\Iam\Input\GetUserRequest;
use AsyncAws\Iam\Input\ListUsersRequest;
use AsyncAws\Iam\Input\UpdateUserRequest;
use AsyncAws\Iam\Result\CreateUserResponse;
use AsyncAws\Iam\Result\GetUserResponse;
use AsyncAws\Iam\Result\ListUsersResponse;
use AsyncAws\Iam\ValueObject\User;

class IamClient extends AbstractApi
{
    /**
     * Creates a new IAM user for your AWS account.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#createuser
     *
     * @param array{
     *   Path?: string,
     *   UserName: string,
     *   PermissionsBoundary?: string,
     *   Tags?: \AsyncAws\Iam\ValueObject\Tag[],
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
     * Deletes the specified IAM user. Unlike the AWS Management Console, when you delete a user programmatically, you must
     * delete the items attached to the user manually, or the deletion fails. For more information, see Deleting an IAM
     * User. Before attempting to delete a user, remove the following items:.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_users_manage.html#id_users_deleting_cli
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
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-iam-2010-05-08.html#listusers
     *
     * @param array{
     *   PathPrefix?: string,
     *   Marker?: string,
     *   MaxItems?: int,
     *   @region?: string,
     * }|ListUsersRequest $input
     *
     * @return \Traversable<User> & ListUsersResponse
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
                'signVersions' => [
                    0 => 'v4',
                ],
            ];
        }

        switch ($region) {
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => 'https://iam.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'iam',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://iam.cn-north-1.amazonaws.com.cn',
                    'signRegion' => 'cn-north-1',
                    'signService' => 'iam',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://iam.us-gov.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'iam',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://iam.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'iam',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://iam.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'iam',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'iam-fips':
                return [
                    'endpoint' => 'https://iam-fips.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'iam',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Iam".', $region));
    }

    protected function getServiceCode(): string
    {
        return 'iam';
    }

    protected function getSignatureScopeName(): string
    {
        return 'iam';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
