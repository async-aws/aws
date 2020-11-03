<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Input\ListCollectionsRequest;
use AsyncAws\Rekognition\RekognitionClient;

/**
 * @implements \IteratorAggregate<string>
 */
class ListCollectionsResponse extends Result implements \IteratorAggregate
{
    /**
     * An array of collection IDs.
     */
    private $CollectionIds = [];

    /**
     * If the result is truncated, the response provides a `NextToken` that you can use in the subsequent request to fetch
     * the next set of collection IDs.
     */
    private $NextToken;

    /**
     * Version numbers of the face detection models associated with the collections in the array `CollectionIds`. For
     * example, the value of `FaceModelVersions[2]` is the version number for the face detection model used by the
     * collection in `CollectionId[2]`.
     */
    private $FaceModelVersions = [];

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<string>
     */
    public function getCollectionIds(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->CollectionIds;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof RekognitionClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListCollectionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->ListCollections($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getCollectionIds(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * @return string[]
     */
    public function getFaceModelVersions(): array
    {
        $this->initialize();

        return $this->FaceModelVersions;
    }

    /**
     * Iterates over CollectionIds.
     *
     * @return \Traversable<string>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof RekognitionClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListCollectionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->ListCollections($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getCollectionIds(true);

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

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->CollectionIds = empty($data['CollectionIds']) ? [] : $this->populateResultCollectionIdList($data['CollectionIds']);
        $this->NextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
        $this->FaceModelVersions = empty($data['FaceModelVersions']) ? [] : $this->populateResultFaceModelVersionList($data['FaceModelVersions']);
    }

    /**
     * @return string[]
     */
    private function populateResultCollectionIdList(array $json): array
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

    /**
     * @return string[]
     */
    private function populateResultFaceModelVersionList(array $json): array
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
