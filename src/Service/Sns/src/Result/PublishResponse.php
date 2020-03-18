<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PublishResponse extends Result
{
    /**
     * Unique identifier assigned to the published message.
     */
    private $MessageId;

    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->MessageId;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->PublishResult;

        $this->MessageId = ($v = $data->MessageId) ? (string) $v : null;
    }
}
