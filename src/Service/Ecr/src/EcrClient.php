<?php

namespace AsyncAws\Ecr;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Ecr\Exception\InvalidParameterException;
use AsyncAws\Ecr\Exception\ServerException;
use AsyncAws\Ecr\Input\GetAuthorizationTokenRequest;
use AsyncAws\Ecr\Result\GetAuthorizationTokenResponse;

class EcrClient extends AbstractApi
{
    /**
     * Retrieves an authorization token. An authorization token represents your IAM authentication credentials and can be
     * used to access any Amazon ECR registry that your IAM principal has access to. The authorization token is valid for 12
     * hours.
     *
     * The `authorizationToken` returned is a base64 encoded string that can be decoded and used in a `docker login` command
     * to authenticate to a registry. The CLI offers an `get-login-password` command that simplifies the login process. For
     * more information, see Registry authentication [^1] in the *Amazon Elastic Container Registry User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonECR/latest/userguide/Registries.html#registry_auth
     *
     * @see https://docs.aws.amazon.com/AmazonECR/latest/APIReference/API_GetAuthorizationToken.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-api.ecr-2015-09-21.html#getauthorizationtoken
     *
     * @param array{
     *   registryIds?: string[]|null,
     *   '@region'?: string|null,
     * }|GetAuthorizationTokenRequest $input
     *
     * @throws InvalidParameterException
     * @throws ServerException
     */
    public function getAuthorizationToken($input = []): GetAuthorizationTokenResponse
    {
        $input = GetAuthorizationTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetAuthorizationToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterException' => InvalidParameterException::class,
            'ServerException' => ServerException::class,
        ]]));

        return new GetAuthorizationTokenResponse($response);
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
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://api.ecr.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'dkr-us-east-1':
                return [
                    'endpoint' => 'https://api.ecr.dkr-us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'dkr-us-east-2':
                return [
                    'endpoint' => 'https://api.ecr.dkr-us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'dkr-us-west-1':
                return [
                    'endpoint' => 'https://api.ecr.dkr-us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'dkr-us-west-2':
                return [
                    'endpoint' => 'https://api.ecr.dkr-us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://api.ecr.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'dkr-us-gov-east-1':
                return [
                    'endpoint' => 'https://api.ecr.dkr-us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'dkr-us-gov-west-1':
                return [
                    'endpoint' => 'https://api.ecr.dkr-us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-east-1':
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-east-2':
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://ecr-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-west-1':
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-west-2':
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://ecr-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-gov-east-1':
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-gov-west-1':
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://api.ecr.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://api.ecr.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://api.ecr.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://api.ecr.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://api.ecr.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'ecr',
            'signVersions' => ['v4'],
        ];
    }
}
