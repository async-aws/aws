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
     * Iterates over QueueUrls.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        $this->initialize();

        while (true) {
            yield from $this->QueueUrls;

            // TODO load next results
            break;
        }
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<string>
     */
    public function getQueueUrls(bool $currentPageOnly = false): iterable
    {
        $this->initialize();

        if ($currentPageOnly) {
            return $this->QueueUrls;
        }
        while (true) {
            yield from $this->QueueUrls;

            // TODO load next results
            break;
        }
    }

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->ListQueuesResult;

        $this->QueueUrls = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
                $items[] = $this->xmlValueOrNull($item, 'string');
            }

            return $items;
        })($data->QueueUrl);
    }
}
