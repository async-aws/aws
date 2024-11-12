<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Input\ListBucketsRequest;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\Bucket;
use AsyncAws\S3\ValueObject\Owner;

/**
 * @implements \IteratorAggregate<Bucket>
 */
class ListBucketsOutput extends Result implements \IteratorAggregate
{
    /**
     * The list of buckets owned by the requester.
     *
     * @var Bucket[]
     */
    private $buckets;

    /**
     * The owner of the buckets listed.
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * `ContinuationToken` is included in the response when there are more buckets that can be listed with pagination. The
     * next `ListBuckets` request to Amazon S3 can be continued with this `ContinuationToken`. `ContinuationToken` is
     * obfuscated and is not a real bucket.
     *
     * @var string|null
     */
    private $continuationToken;

    /**
     * If `Prefix` was sent with the request, it is included in the response.
     *
     * All bucket names in the response begin with the specified bucket name prefix.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Bucket>
     */
    public function getBuckets(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->buckets;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListBucketsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->continuationToken) {
                $input->setContinuationToken($page->continuationToken);

                $this->registerPrefetch($nextPage = $client->listBuckets($input));
            } else {
                $nextPage = null;
            }

            yield from $page->buckets;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getContinuationToken(): ?string
    {
        $this->initialize();

        return $this->continuationToken;
    }

    /**
     * Iterates over Buckets.
     *
     * @return \Traversable<Bucket>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getBuckets();
    }

    public function getOwner(): ?Owner
    {
        $this->initialize();

        return $this->owner;
    }

    public function getPrefix(): ?string
    {
        $this->initialize();

        return $this->prefix;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->buckets = (0 === ($v = $data->Buckets)->count()) ? [] : $this->populateResultBuckets($v);
        $this->owner = 0 === $data->Owner->count() ? null : $this->populateResultOwner($data->Owner);
        $this->continuationToken = (null !== $v = $data->ContinuationToken[0]) ? (string) $v : null;
        $this->prefix = (null !== $v = $data->Prefix[0]) ? (string) $v : null;
    }

    private function populateResultBucket(\SimpleXMLElement $xml): Bucket
    {
        return new Bucket([
            'Name' => (null !== $v = $xml->Name[0]) ? (string) $v : null,
            'CreationDate' => (null !== $v = $xml->CreationDate[0]) ? new \DateTimeImmutable((string) $v) : null,
            'BucketRegion' => (null !== $v = $xml->BucketRegion[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Bucket[]
     */
    private function populateResultBuckets(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->Bucket as $item) {
            $items[] = $this->populateResultBucket($item);
        }

        return $items;
    }

    private function populateResultOwner(\SimpleXMLElement $xml): Owner
    {
        return new Owner([
            'DisplayName' => (null !== $v = $xml->DisplayName[0]) ? (string) $v : null,
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
        ]);
    }
}
