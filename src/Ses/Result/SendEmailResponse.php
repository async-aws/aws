<?php

namespace AsyncAws\Ses\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $this->MessageId = ($v = $data->MessageId) ? (string) $v : null;
    }
}
