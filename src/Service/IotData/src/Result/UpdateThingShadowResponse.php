<?php

namespace AsyncAws\IotData\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The output from the UpdateThingShadow operation.
 */
class UpdateThingShadowResponse extends Result
{
    /**
     * The state information, in JSON format.
     */
    private $payload;

    public function getPayload(): ?string
    {
        $this->initialize();

        return $this->payload;
    }

    protected function populateResult(Response $response): void
    {
        $this->payload = $response->getContent();
    }
}
