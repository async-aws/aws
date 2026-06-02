<?php

namespace AsyncAws\Sso;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Sso\Exception\InvalidRequestException;
use AsyncAws\Sso\Exception\ResourceNotFoundException;
use AsyncAws\Sso\Exception\TooManyRequestsException;
use AsyncAws\Sso\Exception\UnauthorizedException;
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;
use AsyncAws\Sso\Input\ListAccountRolesRequest;
use AsyncAws\Sso\Input\ListAccountsRequest;
use AsyncAws\Sso\Result\GetRoleCredentialsResponse;
use AsyncAws\Sso\Result\ListAccountRolesResponse;
use AsyncAws\Sso\Result\ListAccountsResponse;

class SsoClient extends AbstractApi
{
    /**
     * Returns the STS short-term credentials for a given role name that is assigned to the user.
     *
     * @see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_GetRoleCredentials.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-portal.sso-2019-06-10.html#getrolecredentials
     *
     * @param array{
     *   roleName: string,
     *   accountId: string,
     *   accessToken: string,
     *   '@region'?: string|null,
     * }|GetRoleCredentialsRequest $input
     *
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function getRoleCredentials($input): GetRoleCredentialsResponse
    {
        $input = GetRoleCredentialsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetRoleCredentials', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new GetRoleCredentialsResponse($response);
    }

    /**
     * Lists all roles that are assigned to the user for a given AWS account.
     *
     * @see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccountRoles.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-portal.sso-2019-06-10.html#listaccountroles
     *
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   accessToken: string,
     *   accountId: string,
     *   '@region'?: string|null,
     * }|ListAccountRolesRequest $input
     *
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function listAccountRoles($input): ListAccountRolesResponse
    {
        $input = ListAccountRolesRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListAccountRoles', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new ListAccountRolesResponse($response, $this, $input);
    }

    /**
     * Lists all AWS accounts assigned to the user. These AWS accounts are assigned by the administrator of the account. For
     * more information, see Assign User Access [^1] in the *IAM Identity Center User Guide*. This operation returns a
     * paginated response.
     *
     * [^1]: https://docs.aws.amazon.com/singlesignon/latest/userguide/useraccess.html#assignusers
     *
     * @see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_ListAccounts.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-portal.sso-2019-06-10.html#listaccounts
     *
     * @param array{
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   accessToken: string,
     *   '@region'?: string|null,
     * }|ListAccountsRequest $input
     *
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function listAccounts($input): ListAccountsResponse
    {
        $input = ListAccountsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListAccounts', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new ListAccountsResponse($response, $this, $input);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://portal.sso.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'awsssoportal',
                    'signVersions' => ['v4'],
                ];
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://portal.sso.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'awsssoportal',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://portal.sso.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'awsssoportal',
            'signVersions' => ['v4'],
        ];
    }
}
