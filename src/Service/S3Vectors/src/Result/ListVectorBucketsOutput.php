<?php

namespace AsyncAws\S3Vectors\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3Vectors\Input\ListVectorBucketsInput;
use AsyncAws\S3Vectors\S3VectorsClient;
use AsyncAws\S3Vectors\ValueObject\VectorBucketSummary;

/**
 * @implements \IteratorAggregate<VectorBucketSummary>
 */
class ListVectorBucketsOutput extends Result implements \IteratorAggregate
{
    /**
     * The element is included in the response when there are more buckets to be listed with pagination.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The list of vector buckets owned by the requester.
     *
     * @var VectorBucketSummary[]
     */
    private $vectorBuckets;

    /**
     * Iterates over vectorBuckets.
     *
     * @return \Traversable<VectorBucketSummary>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getVectorBuckets();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<VectorBucketSummary>
     */
    public function getVectorBuckets(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->vectorBuckets;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3VectorsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListVectorBucketsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listVectorBuckets($input));
            } else {
                $nextPage = null;
            }

            yield from $page->vectorBuckets;

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
        $this->vectorBuckets = $this->populateResultListVectorBucketsOutputList($data['vectorBuckets'] ?? []);
    }

    /**
     * @return VectorBucketSummary[]
     */
    private function populateResultListVectorBucketsOutputList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultVectorBucketSummary($item);
        }

        return $items;
    }

    private function populateResultVectorBucketSummary(array $json): VectorBucketSummary
    {
        return new VectorBucketSummary([
            'vectorBucketName' => (string) $json['vectorBucketName'],
            'vectorBucketArn' => (string) $json['vectorBucketArn'],
            'creationTime' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['creationTime'])),
        ]);
    }
}
