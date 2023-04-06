<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\ListQueryExecutionsInput;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<string>
 */
class ListQueryExecutionsOutput extends Result implements \IteratorAggregate
{
    /**
     * The unique IDs of each query execution as an array of strings.
     */
    private $queryExecutionIds;

    /**
     * A token to be used by the next request if this request is truncated.
     */
    private $nextToken;

    /**
     * Iterates over QueryExecutionIds.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getQueryExecutionIds();
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
    public function getQueryExecutionIds(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->queryExecutionIds;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof AthenaClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListQueryExecutionsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listQueryExecutions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->queryExecutionIds;

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

        $this->queryExecutionIds = empty($data['QueryExecutionIds']) ? [] : $this->populateResultQueryExecutionIdList($data['QueryExecutionIds']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    /**
     * @return string[]
     */
    private function populateResultQueryExecutionIdList(array $json): array
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
