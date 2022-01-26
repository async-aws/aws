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
     * **IMPORTANT** Starting on May 15, 2020, this parameter will be deprecated. This parameter will be an empty list after
     * the deprecation occurs.
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

    /**
     * @return FilteredLogEvent[]
     */
    private function populateResultFilteredLogEvents(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new FilteredLogEvent([
                'logStreamName' => isset($item['logStreamName']) ? (string) $item['logStreamName'] : null,
                'timestamp' => isset($item['timestamp']) ? (string) $item['timestamp'] : null,
                'message' => isset($item['message']) ? (string) $item['message'] : null,
                'ingestionTime' => isset($item['ingestionTime']) ? (string) $item['ingestionTime'] : null,
                'eventId' => isset($item['eventId']) ? (string) $item['eventId'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return SearchedLogStream[]
     */
    private function populateResultSearchedLogStreams(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new SearchedLogStream([
                'logStreamName' => isset($item['logStreamName']) ? (string) $item['logStreamName'] : null,
                'searchedCompletely' => isset($item['searchedCompletely']) ? filter_var($item['searchedCompletely'], \FILTER_VALIDATE_BOOLEAN) : null,
            ]);
        }

        return $items;
    }
}
