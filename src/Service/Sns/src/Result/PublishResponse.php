<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PublishResponse extends Result
{
    private $MessageId;

    /**
     * Unique identifier assigned to the published message.
     */
    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->MessageId;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->PublishResult;

        $this->MessageId = ($v = $data->MessageId) ? (string) $v : null;
    }
}
