<?php

namespace AsyncAws\DynamoDb\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\DynamoDb\DynamoDbClient;
use AsyncAws\DynamoDb\Input\ListTablesInput;

/**
 * @implements \IteratorAggregate<string>
 */
class ListTablesOutput extends Result implements \IteratorAggregate
{
    /**
     * The names of the tables associated with the current account at the current endpoint. The maximum size of this array
     * is 100.
     */
    private $TableNames = [];

    /**
     * The name of the last table in the current page of results. Use this value as the `ExclusiveStartTableName` in a new
     * request to obtain the next page of results, until all the table names are returned.
     */
    private $LastEvaluatedTableName;

    /**
     * Iterates over TableNames.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof DynamoDbClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListTablesInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getLastEvaluatedTableName()) {
                $input->setExclusiveStartTableName($page->getLastEvaluatedTableName());

                $this->registerPrefetch($nextPage = $client->ListTables($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getTableNames(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getLastEvaluatedTableName(): ?string
    {
        $this->initialize();

        return $this->LastEvaluatedTableName;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<string>
     */
    public function getTableNames(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->TableNames;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof DynamoDbClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListTablesInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getLastEvaluatedTableName()) {
                $input->setExclusiveStartTableName($page->getLastEvaluatedTableName());

                $this->registerPrefetch($nextPage = $client->ListTables($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getTableNames(true);

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

        $this->TableNames = empty($data['TableNames']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $a = isset($item) ? (string) $item : null;
                if (null !== $a) {
                    $items[] = $a;
                }
            }

            return $items;
        })($data['TableNames']);
        $this->LastEvaluatedTableName = isset($data['LastEvaluatedTableName']) ? (string) $data['LastEvaluatedTableName'] : null;
    }
}
