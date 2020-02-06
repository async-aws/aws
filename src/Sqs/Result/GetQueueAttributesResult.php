<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetQueueAttributesResult extends Result
{
    /**
     * A map of attributes to their respective values.
     */
    private $Attributes;

    public function getAttributes(): array
    {
        $this->initialize();

        return $this->Attributes;
    }

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->GetQueueAttributesResult;

        $this->Attributes = $this->xmlValueOrNull($data->Attributes, 'array');
    }
}
