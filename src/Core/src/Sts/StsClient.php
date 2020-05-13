<?php

namespace AsyncAws\Core\Sts;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\AssumeRoleWithWebIdentityRequest;
use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Sts\Result\AssumeRoleResponse;
use AsyncAws\Core\Sts\Result\AssumeRoleWithWebIdentityResponse;
use AsyncAws\Core\Sts\Result\GetCallerIdentityResponse;

class StsClient extends AbstractApi
{
    /**
     * Returns a set of temporary security credentials that you can use to access AWS resources that you might not normally
     * have access to. These temporary credentials consist of an access key ID, a secret access key, and a security token.
     * Typically, you use `AssumeRole` within your account or for cross-account access. For a comparison of `AssumeRole`
     * with other API operations that produce temporary credentials, see Requesting Temporary Security Credentials and
     * Comparing the AWS STS API operations in the *IAM User Guide*.
     *
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_request.html
     * @see https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_request.html#stsapi_comparison
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sts-2011-06-15.html#assumerole
     *
     * @param array{
     *   RoleArn: string,
     *   RoleSessionName: string,
     *   PolicyArns?: \AsyncAws\Core\Sts\ValueObject\PolicyDescriptorType[],
     *   Policy?: string,
     *   DurationSeconds?: int,
     *   Tags?: \AsyncAws\Core\Sts\ValueObject\Tag[],
     *   TransitiveTagKeys?: string[],
     *   ExternalId?: string,
     *   SerialNumber?: string,
     *   TokenCode?: string,
     *   @region?: string,
     * }|AssumeRoleRequest $input
     */
    public function assumeRole($input): AssumeRoleResponse
    {
        $input = AssumeRoleRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AssumeRole', 'region' => $input->getRegion()]));

        return new AssumeRoleResponse($response);
    }

    /**
     * Returns a set of temporary security credentials for users who have been authenticated in a mobile or web application
     * with a web identity provider. Example providers include Amazon Cognito, Login with Amazon, Facebook, Google, or any
     * OpenID Connect-compatible identity provider.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sts-2011-06-15.html#assumerolewithwebidentity
     *
     * @param array{
     *   RoleArn: string,
     *   RoleSessionName: string,
     *   WebIdentityToken: string,
     *   ProviderId?: string,
     *   PolicyArns?: \AsyncAws\Core\Sts\ValueObject\PolicyDescriptorType[],
     *   Policy?: string,
     *   DurationSeconds?: int,
     *   @region?: string,
     * }|AssumeRoleWithWebIdentityRequest $input
     */
    public function assumeRoleWithWebIdentity($input): AssumeRoleWithWebIdentityResponse
    {
        $input = AssumeRoleWithWebIdentityRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'AssumeRoleWithWebIdentity', 'region' => $input->getRegion()]));

        return new AssumeRoleWithWebIdentityResponse($response);
    }

    /**
     * Returns details about the IAM user or role whose credentials are used to call the operation.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sts-2011-06-15.html#getcalleridentity
     *
     * @param array{
     *   @region?: string,
     * }|GetCallerIdentityRequest $input
     */
    public function getCallerIdentity($input = []): GetCallerIdentityResponse
    {
        $input = GetCallerIdentityRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetCallerIdentity', 'region' => $input->getRegion()]));

        return new GetCallerIdentityResponse($response);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            return [
                'endpoint' => 'https://sts.amazonaws.com',
                'signRegion' => 'us-east-1',
                'signService' => 'sts',
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
                    'endpoint' => 'https://sts.%region%.amazonaws.com',
                    'signRegion' => $region,
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://sts.%region%.amazonaws.com.cn',
                    'signRegion' => $region,
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://sts.%region%.amazonaws.com',
                    'signRegion' => $region,
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://sts.%region%.c2s.ic.gov',
                    'signRegion' => $region,
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://sts.%region%.sc2s.sgov.gov',
                    'signRegion' => $region,
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://sts-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://sts-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://sts-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://sts-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'sts',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Sts".', $region));
    }

    protected function getServiceCode(): string
    {
        return 'sts';
    }

    protected function getSignatureScopeName(): string
    {
        return 'sts';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
