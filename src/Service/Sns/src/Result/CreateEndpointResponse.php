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
    private $endpointArn;

    public function getEndpointArn(): ?string
    {
        $this->initialize();

        return $this->endpointArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->CreatePlatformEndpointResult;

        $this->endpointArn = ($v = $data->EndpointArn) ? (string) $v : null;
    }
}
