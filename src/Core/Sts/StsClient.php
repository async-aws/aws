<?php

namespace AsyncAws\Core\Sts;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Sts\Result\AssumeRoleResponse;
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
     *   PolicyArns?: \AsyncAws\Core\Sts\Input\PolicyDescriptorType[],
     *   Policy?: string,
     *   DurationSeconds?: int,
     *   Tags?: \AsyncAws\Core\Sts\Input\Tag[],
     *   TransitiveTagKeys?: string[],
     *   ExternalId?: string,
     *   SerialNumber?: string,
     *   TokenCode?: string,
     * }|AssumeRoleRequest $input
     */
    public function assumeRole($input): AssumeRoleResponse
    {
        $input = AssumeRoleRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new AssumeRoleResponse($response, $this->httpClient);
    }

    /**
     * Returns details about the IAM user or role whose credentials are used to call the operation.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sts-2011-06-15.html#getcalleridentity
     *
     * @param array|GetCallerIdentityRequest $input
     */
    public function getCallerIdentity($input = []): GetCallerIdentityResponse
    {
        $input = GetCallerIdentityRequest::create($input);
        $input->validate();

        $response = $this->getResponse(
            'POST',
            $input->requestBody(),
            $input->requestHeaders(),
            $this->getEndpoint($input->requestUri(), $input->requestQuery())
        );

        return new GetCallerIdentityResponse($response, $this->httpClient);
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
