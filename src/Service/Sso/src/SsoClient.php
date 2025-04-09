<?php

namespace AsyncAws\Sso;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Sso\Exception\InvalidRequestException;
use AsyncAws\Sso\Exception\ResourceNotFoundException;
use AsyncAws\Sso\Exception\TooManyRequestsException;
use AsyncAws\Sso\Exception\UnauthorizedException;
use AsyncAws\Sso\Input\GetRoleCredentialsRequest;
use AsyncAws\Sso\Result\GetRoleCredentialsResponse;

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
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-northeast-3':
            case 'ap-south-1':
            case 'ap-south-2':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ap-southeast-3':
            case 'ap-southeast-4':
            case 'ap-southeast-5':
            case 'ca-central-1':
            case 'ca-west-1':
            case 'eu-central-1':
            case 'eu-central-2':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-south-2':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'il-central-1':
            case 'me-central-1':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-gov-east-1':
            case 'us-gov-west-1':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://portal.sso.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'awsssoportal',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://portal.sso.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'awsssoportal',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(\sprintf('The region "%s" is not supported by "Sso".', $region));
    }
}
