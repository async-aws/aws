<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<string>
 */
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

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListQueuesResult;

        $this->QueueUrls = !$data->QueueUrl ? [] : $this->populateResultQueueUrlList($data->QueueUrl);
    }

    /**
     * @return string[]
     */
    private function populateResultQueueUrlList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
