<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\Input\ListIndexesInput;
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\ValueObject\IndexSummary;

/**
 * @implements \IteratorAggregate<IndexSummary>
 */
class ListIndexesOutput extends Result implements \IteratorAggregate
{
    /**
     * The next pagination token.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The attributes of the vector indexes.
     *
     * @var IndexSummary[]
     */
    private $indexes;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<IndexSummary>
     */
    public function getIndexes(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->indexes;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3VectorsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListIndexesInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listIndexes($input));
            } else {
                $nextPage = null;
            }

            yield from $page->indexes;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over indexes.
     *
     * @return \Traversable<IndexSummary>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getIndexes();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
        $this->indexes = $this->populateResultListIndexesOutputList($data['indexes'] ?? []);
    }

    private function populateResultIndexSummary(array $json): IndexSummary
    {
        return new IndexSummary([
            'vectorBucketName' => (string) $json['vectorBucketName'],
            'indexName' => (string) $json['indexName'],
            'indexArn' => (string) $json['indexArn'],
            'creationTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['creationTime'])),
        ]);
    }

    /**
     * @return IndexSummary[]
     */
    private function populateResultListIndexesOutputList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultIndexSummary($item);
        }

        return $items;
    }
}
