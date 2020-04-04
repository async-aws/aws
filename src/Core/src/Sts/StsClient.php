<?php

namespace AsyncAws\Core\Sts;

use AsyncAws\Core\AbstractApi;
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
