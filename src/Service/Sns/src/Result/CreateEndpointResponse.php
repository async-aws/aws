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
     *
     * @var string|null
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

        $this->endpointArn = (null !== $v = $data->EndpointArn[0]) ? (string) $v : null;
    }
}
