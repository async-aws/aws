<?php

namespace AsyncAws\CloudWatchLogs\Result;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\FilterLogEventsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\FilteredLogEvent;
use AsyncAws\CloudWatchLogs\ValueObject\SearchedLogStream;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<FilteredLogEvent|SearchedLogStream>
 */
class FilterLogEventsResponse extends Result implements \IteratorAggregate
{
    /**
     * The matched events.
     */
    private $events;

    /**
     * **Important** As of May 15, 2020, this parameter is no longer supported. This parameter returns an empty list.
     *
     * Indicates which log streams have been searched and whether each has been searched completely.
     */
    private $searchedLogStreams;

    /**
     * The token to use when requesting the next set of items. The token expires after 24 hours.
     */
    private $nextToken;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<FilteredLogEvent>
     */
    public function getEvents(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->events;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchLogsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof FilterLogEventsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->filterLogEvents($input));
            } else {
                $nextPage = null;
            }

            yield from $page->events;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over events and searchedLogStreams.
     *
     * @return \Traversable<FilteredLogEvent|SearchedLogStream>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof CloudWatchLogsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof FilterLogEventsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->filterLogEvents($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getEvents(true);
            yield from $page->getSearchedLogStreams(true);

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
     * @return iterable<SearchedLogStream>
     */
    public function getSearchedLogStreams(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->searchedLogStreams;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchLogsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof FilterLogEventsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->filterLogEvents($input));
            } else {
                $nextPage = null;
            }

            yield from $page->searchedLogStreams;

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

        $this->events = empty($data['events']) ? [] : $this->populateResultFilteredLogEvents($data['events']);
        $this->searchedLogStreams = empty($data['searchedLogStreams']) ? [] : $this->populateResultSearchedLogStreams($data['searchedLogStreams']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    private function populateResultFilteredLogEvent(array $json): FilteredLogEvent
    {
        return new FilteredLogEvent([
            'logStreamName' => isset($json['logStreamName']) ? (string) $json['logStreamName'] : null,
            'timestamp' => isset($json['timestamp']) ? (string) $json['timestamp'] : null,
            'message' => isset($json['message']) ? (string) $json['message'] : null,
            'ingestionTime' => isset($json['ingestionTime']) ? (string) $json['ingestionTime'] : null,
            'eventId' => isset($json['eventId']) ? (string) $json['eventId'] : null,
        ]);
    }

    /**
     * @return FilteredLogEvent[]
     */
    private function populateResultFilteredLogEvents(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultFilteredLogEvent($item);
        }

        return $items;
    }

    private function populateResultSearchedLogStream(array $json): SearchedLogStream
    {
        return new SearchedLogStream([
            'logStreamName' => isset($json['logStreamName']) ? (string) $json['logStreamName'] : null,
            'searchedCompletely' => isset($json['searchedCompletely']) ? filter_var($json['searchedCompletely'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    /**
     * @return SearchedLogStream[]
     */
    private function populateResultSearchedLogStreams(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSearchedLogStream($item);
        }

        return $items;
    }
}
