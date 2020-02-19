<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ListQueuesResult extends Result implements \IteratorAggregate
{
    /**
     * A list of queue URLs, up to 1,000 entries.
     */
    private $QueueUrls = [];

    /**
     * Ensure current request is resolved and right exception is thrown.
     */
    public function __destruct()
    {
        $this->resolve();
    }

    /**
     * Iterates over QueueUrls.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getQueueUrls();
    }

    /**
     * @return iterable<string>
     */
    public function getQueueUrls(): iterable
    {
        $this->initialize();

        return $this->QueueUrls;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->ListQueuesResult;

        $this->QueueUrls = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = ($v = $item) ? (string) $v : null;
            }

            return $items;
        })($data->QueueUrl);
    }
}
