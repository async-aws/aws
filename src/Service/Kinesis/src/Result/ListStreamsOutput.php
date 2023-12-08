<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Input\ListStreamsInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\ValueObject\StreamModeDetails;
use AsyncAws\Kinesis\ValueObject\StreamSummary;

/**
 * Represents the output for `ListStreams`.
 *
 * @implements \IteratorAggregate<string|StreamSummary>
 */
class ListStreamsOutput extends Result implements \IteratorAggregate
{
    /**
     * The names of the streams that are associated with the Amazon Web Services account making the `ListStreams` request.
     *
     * @var string[]
     */
    private $streamNames;

    /**
     * If set to `true`, there are more streams available to list.
     *
     * @var bool
     */
    private $hasMoreStreams;

    /**
     * @var string|null
     */
    private $nextToken;

    /**
     * @var StreamSummary[]
     */
    private $streamSummaries;

    public function getHasMoreStreams(): bool
    {
        $this->initialize();

        return $this->hasMoreStreams;
    }

    /**
     * Iterates over StreamNames and StreamSummaries.
     *
     * @return \Traversable<string|StreamSummary>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof KinesisClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListStreamsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->hasMoreStreams) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listStreams($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getStreamNames(true);
            yield from $page->getStreamSummaries(true);

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

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<string>
     */
    public function getStreamNames(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->streamNames;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof KinesisClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListStreamsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->hasMoreStreams) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listStreams($input));
            } else {
                $nextPage = null;
            }

            yield from $page->streamNames;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<StreamSummary>
     */
    public function getStreamSummaries(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->streamSummaries;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof KinesisClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListStreamsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->hasMoreStreams) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listStreams($input));
            } else {
                $nextPage = null;
            }

            yield from $page->streamSummaries;

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

        $this->streamNames = $this->populateResultStreamNameList($data['StreamNames'] ?? []);
        $this->hasMoreStreams = filter_var($data['HasMoreStreams'], \FILTER_VALIDATE_BOOLEAN);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
        $this->streamSummaries = empty($data['StreamSummaries']) ? [] : $this->populateResultStreamSummaryList($data['StreamSummaries']);
    }

    private function populateResultStreamModeDetails(array $json): StreamModeDetails
    {
        return new StreamModeDetails([
            'StreamMode' => (string) $json['StreamMode'],
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultStreamNameList(array $json): array
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

    private function populateResultStreamSummary(array $json): StreamSummary
    {
        return new StreamSummary([
            'StreamName' => (string) $json['StreamName'],
            'StreamARN' => (string) $json['StreamARN'],
            'StreamStatus' => (string) $json['StreamStatus'],
            'StreamModeDetails' => empty($json['StreamModeDetails']) ? null : $this->populateResultStreamModeDetails($json['StreamModeDetails']),
            'StreamCreationTimestamp' => (isset($json['StreamCreationTimestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['StreamCreationTimestamp'])))) ? $d : null,
        ]);
    }

    /**
     * @return StreamSummary[]
     */
    private function populateResultStreamSummaryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultStreamSummary($item);
        }

        return $items;
    }
}
