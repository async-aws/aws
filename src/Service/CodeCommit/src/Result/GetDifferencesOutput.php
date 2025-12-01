<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\CodeCommit\CodeCommitClient;
use AsyncAws\CodeCommit\Enum\ChangeTypeEnum;
use AsyncAws\CodeCommit\Input\GetDifferencesInput;
use AsyncAws\CodeCommit\ValueObject\BlobMetadata;
use AsyncAws\CodeCommit\ValueObject\Difference;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<Difference>
 */
class GetDifferencesOutput extends Result implements \IteratorAggregate
{
    /**
     * A data type object that contains information about the differences, including whether the difference is added,
     * modified, or deleted (A, D, M).
     *
     * @var Difference[]
     */
    private $differences;

    /**
     * An enumeration token that can be used in a request to return the next batch of the results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Difference>
     */
    public function getDifferences(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->differences;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CodeCommitClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof GetDifferencesInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->getDifferences($input));
            } else {
                $nextPage = null;
            }

            yield from $page->differences;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over differences.
     *
     * @return \Traversable<Difference>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getDifferences();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->differences = empty($data['differences']) ? [] : $this->populateResultDifferenceList($data['differences']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    private function populateResultBlobMetadata(array $json): BlobMetadata
    {
        return new BlobMetadata([
            'blobId' => isset($json['blobId']) ? (string) $json['blobId'] : null,
            'path' => isset($json['path']) ? (string) $json['path'] : null,
            'mode' => isset($json['mode']) ? (string) $json['mode'] : null,
        ]);
    }

    private function populateResultDifference(array $json): Difference
    {
        return new Difference([
            'beforeBlob' => empty($json['beforeBlob']) ? null : $this->populateResultBlobMetadata($json['beforeBlob']),
            'afterBlob' => empty($json['afterBlob']) ? null : $this->populateResultBlobMetadata($json['afterBlob']),
            'changeType' => isset($json['changeType']) ? (!ChangeTypeEnum::exists((string) $json['changeType']) ? ChangeTypeEnum::UNKNOWN_TO_SDK : (string) $json['changeType']) : null,
        ]);
    }

    /**
     * @return Difference[]
     */
    private function populateResultDifferenceList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultDifference($item);
        }

        return $items;
    }
}
