<?php

namespace AsyncAws\Ecr;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
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
     * @see https://docs.aws.amazon.com/AmazonECR/latest/APIReference/API_GetAuthorizationToken.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-api.ecr-2015-09-21.html#getauthorizationtoken
     *
     * @param array{
     *   registryIds?: string[],
     *   @region?: string,
     * }|GetAuthorizationTokenRequest $input
     *
     * @throws ServerException
     * @throws InvalidParameterException
     */
    public function getAuthorizationToken($input = []): GetAuthorizationTokenResponse
    {
        $input = GetAuthorizationTokenRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetAuthorizationToken', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ServerException' => ServerException::class,
            'InvalidParameterException' => InvalidParameterException::class,
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
            case 'af-south-1':
                return [
                    'endpoint' => 'https://api.ecr.af-south-1.amazonaws.com',
                    'signRegion' => 'af-south-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'ap-east-1':
                return [
                    'endpoint' => 'https://api.ecr.ap-east-1.amazonaws.com',
                    'signRegion' => 'ap-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-1':
                return [
                    'endpoint' => 'https://api.ecr.ap-northeast-1.amazonaws.com',
                    'signRegion' => 'ap-northeast-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'ap-northeast-2':
                return [
                    'endpoint' => 'https://api.ecr.ap-northeast-2.amazonaws.com',
                    'signRegion' => 'ap-northeast-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'ap-south-1':
                return [
                    'endpoint' => 'https://api.ecr.ap-south-1.amazonaws.com',
                    'signRegion' => 'ap-south-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-1':
                return [
                    'endpoint' => 'https://api.ecr.ap-southeast-1.amazonaws.com',
                    'signRegion' => 'ap-southeast-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'ap-southeast-2':
                return [
                    'endpoint' => 'https://api.ecr.ap-southeast-2.amazonaws.com',
                    'signRegion' => 'ap-southeast-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'ca-central-1':
                return [
                    'endpoint' => 'https://api.ecr.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
                return [
                    'endpoint' => 'https://api.ecr.cn-north-1.amazonaws.com.cn',
                    'signRegion' => 'cn-north-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://api.ecr.cn-northwest-1.amazonaws.com.cn',
                    'signRegion' => 'cn-northwest-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eu-central-1':
                return [
                    'endpoint' => 'https://api.ecr.eu-central-1.amazonaws.com',
                    'signRegion' => 'eu-central-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eu-north-1':
                return [
                    'endpoint' => 'https://api.ecr.eu-north-1.amazonaws.com',
                    'signRegion' => 'eu-north-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eu-south-1':
                return [
                    'endpoint' => 'https://api.ecr.eu-south-1.amazonaws.com',
                    'signRegion' => 'eu-south-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-1':
                return [
                    'endpoint' => 'https://api.ecr.eu-west-1.amazonaws.com',
                    'signRegion' => 'eu-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-2':
                return [
                    'endpoint' => 'https://api.ecr.eu-west-2.amazonaws.com',
                    'signRegion' => 'eu-west-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'eu-west-3':
                return [
                    'endpoint' => 'https://api.ecr.eu-west-3.amazonaws.com',
                    'signRegion' => 'eu-west-3',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-east-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-east-2':
                return [
                    'endpoint' => 'https://ecr-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-gov-east-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-gov-west-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-west-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-dkr-us-west-2':
                return [
                    'endpoint' => 'https://ecr-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://ecr-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://ecr-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://ecr-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'me-south-1':
                return [
                    'endpoint' => 'https://api.ecr.me-south-1.amazonaws.com',
                    'signRegion' => 'me-south-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'sa-east-1':
                return [
                    'endpoint' => 'https://api.ecr.sa-east-1.amazonaws.com',
                    'signRegion' => 'sa-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1':
                return [
                    'endpoint' => 'https://api.ecr.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2':
                return [
                    'endpoint' => 'https://api.ecr.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://api.ecr.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://api.ecr.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://api.ecr.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://api.ecr.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1':
                return [
                    'endpoint' => 'https://api.ecr.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2':
                return [
                    'endpoint' => 'https://api.ecr.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'ecr',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Ecr".', $region));
    }
}
