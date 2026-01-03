<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\Input\ListVectorsInput;
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\ValueObject\ListOutputVector;
use AsyncAws\S3Vectors\ValueObject\VectorData;

/**
 * @implements \IteratorAggregate<ListOutputVector>
 */
class ListVectorsOutput extends Result implements \IteratorAggregate
{
    /**
     * Pagination token to be used in the subsequent request. The field is empty if no further pagination is required.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Vectors in the current segment.
     *
     * @var ListOutputVector[]
     */
    private $vectors;

    /**
     * Iterates over vectors.
     *
     * @return \Traversable<ListOutputVector>
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
     * @return iterable<ListOutputVector>
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
        if (!$this->input instanceof ListVectorsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listVectors($input));
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

        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
        $this->vectors = $this->populateResultListVectorsOutputList($data['vectors'] ?? []);
    }

    /**
     * @return float[]
     */
    private function populateResultFloat32VectorData(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (float) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultListOutputVector(array $json): ListOutputVector
    {
        return new ListOutputVector([
            'key' => (string) $json['key'],
            'data' => empty($json['data']) ? null : $this->populateResultVectorData($json['data']),
            'metadata' => $json['metadata'] ?? null,
        ]);
    }

    /**
     * @return ListOutputVector[]
     */
    private function populateResultListVectorsOutputList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultListOutputVector($item);
        }

        return $items;
    }

    private function populateResultVectorData(array $json): VectorData
    {
        return new VectorData([
            'float32' => !isset($json['float32']) ? null : $this->populateResultFloat32VectorData($json['float32']),
        ]);
    }
}
