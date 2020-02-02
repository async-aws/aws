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

        return new AssumeRoleResponse($response);
    }

    /**
     * @param array{
     * }|GetCallerIdentityRequest $input
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

        return new GetCallerIdentityResponse($response);
    }

    protected function getServiceCode(): string
    {
        return 'sts';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
