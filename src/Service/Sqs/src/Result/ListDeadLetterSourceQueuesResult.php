<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Input\ListDeadLetterSourceQueuesRequest;
use AsyncAws\Sqs\SqsClient;

/**
 * A list of your dead letter source queues.
 *
 * @implements \IteratorAggregate<string>
 */
class ListDeadLetterSourceQueuesResult extends Result implements \IteratorAggregate
{
    /**
     * A list of source queue URLs that have the `RedrivePolicy` queue attribute configured with a dead-letter queue.
     *
     * @var string[]
     */
    private $queueUrls;

    /**
     * Pagination token to include in the next request. Token value is `null` if there are no additional results to request,
     * or if you did not set `MaxResults` in the request.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over queueUrls.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getQueueUrls();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
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
            yield from $this->queueUrls;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof SqsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListDeadLetterSourceQueuesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listDeadLetterSourceQueues($input));
            } else {
                $nextPage = null;
            }

            yield from $page->queueUrls;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->queueUrls = $this->populateResultQueueUrlList($data['queueUrls'] ?? []);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    /**
     * @return string[]
     */
    private function populateResultQueueUrlList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
