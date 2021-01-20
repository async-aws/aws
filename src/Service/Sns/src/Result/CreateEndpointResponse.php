<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Response from CreateEndpoint action.
 */
class CreateEndpointResponse extends Result
{
    /**
     * EndpointArn returned from CreateEndpoint action.
     */
    private $EndpointArn;

    public function getEndpointArn(): ?string
    {
        $this->initialize();

        return $this->EndpointArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreatePlatformEndpointResult;

        $this->EndpointArn = ($v = $data->EndpointArn) ? (string) $v : null;
    }
}
