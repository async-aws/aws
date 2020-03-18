<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class SendEmailResponse extends Result
{
    /**
     * A unique identifier for the message that is generated when the message is accepted.
     */
    private $MessageId;

    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->MessageId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->MessageId = isset($data['MessageId']) ? (string) $data['MessageId'] : null;
    }
}
