<?php

namespace AsyncAws\Core;

use AsyncAws\Core\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Result\GetCallerIdentityResponse;

class StsClient extends AbstractApi
{
    protected function getServiceCode(): string
    {
        return 'sts';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
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
}
