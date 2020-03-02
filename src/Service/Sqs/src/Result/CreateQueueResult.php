<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CreateQueueResult extends Result
{
    private $QueueUrl;

    /**
     * The URL of the created Amazon SQS queue.
     */
    public function getQueueUrl(): ?string
    {
        $this->initialize();

        return $this->QueueUrl;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->CreateQueueResult;

        $this->QueueUrl = ($v = $data->QueueUrl) ? (string) $v : null;
    }
}
