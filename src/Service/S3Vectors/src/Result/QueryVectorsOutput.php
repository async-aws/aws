<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\Enum\DistanceMetric;
use AsyncAws\S3Vectors\Input\QueryVectorsInput;
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\ValueObject\QueryOutputVector;

/**
 * @implements \IteratorAggregate<QueryOutputVector>
 */
class QueryVectorsOutput extends Result implements \IteratorAggregate
{
    /**
     * The vectors in the approximate nearest neighbor search.
     *
     * @var QueryOutputVector[]
     */
    private $vectors;

    /**
     * The distance metric that was used for the similarity search calculation. This is the same distance metric that was
     * configured for the vector index when it was created.
     *
     * @var DistanceMetric::*
     */
    private $distanceMetric;

    /**
     * Pagination token to be used in the subsequent page request. The field is empty if no further pagination is required.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @return DistanceMetric::*
     */
    public function getDistanceMetric(): string
    {
        $this->initialize();

        return $this->distanceMetric;
    }

    /**
     * Iterates over vectors.
     *
     * @return \Traversable<QueryOutputVector>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getVectors();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<QueryOutputVector>
     */
    public function getVectors(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->vectors;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3VectorsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof QueryVectorsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->queryVectors($input));
            } else {
                $nextPage = null;
            }

            yield from $page->vectors;

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

        $this->vectors = $this->populateResultQueryVectorsOutputList($data['vectors'] ?? []);
        $this->distanceMetric = !DistanceMetric::exists((string) $data['distanceMetric']) ? DistanceMetric::UNKNOWN_TO_SDK : (string) $data['distanceMetric'];
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultQueryOutputVector(array $json): QueryOutputVector
    {
        return new QueryOutputVector([
            'distance' => isset($json['distance']) ? (float) $json['distance'] : null,
            'key' => (string) $json['key'],
            'metadata' => $json['metadata'] ?? null,
        ]);
    }

    /**
     * @return QueryOutputVector[]
     */
    private function populateResultQueryVectorsOutputList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultQueryOutputVector($item);
        }

        return $items;
    }
}
