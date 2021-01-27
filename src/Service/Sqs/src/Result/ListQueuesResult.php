<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Input\ListQueuesRequest;
use AsyncAws\Sqs\SqsClient;

/**
 * A list of your queues.
 *
 * @implements \IteratorAggregate<string>
 */
class ListQueuesResult extends Result implements \IteratorAggregate
{
    /**
     * A list of queue URLs, up to 1,000 entries, or the value of MaxResults that you sent in the request.
     */
    private $QueueUrls = [];

    /**
     * Pagination token to include in the next request. Token value is `null` if there are no additional results to request,
     * or if you did not set `MaxResults` in the request.
     */
    private $NextToken;

    /**
     * Iterates over QueueUrls.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof SqsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListQueuesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->ListQueues($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getQueueUrls(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->NextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<string>
     */
    public function getQueueUrls(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->QueueUrls;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SqsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListQueuesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->ListQueues($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getQueueUrls(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListQueuesResult;

        $this->QueueUrls = !$data->QueueUrl ? [] : $this->populateResultQueueUrlList($data->QueueUrl);
        $this->NextToken = ($v = $data->NextToken) ? (string) $v : null;
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
