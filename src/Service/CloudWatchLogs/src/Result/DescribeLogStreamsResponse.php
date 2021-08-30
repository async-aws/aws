<?php

namespace AsyncAws\CloudWatchLogs\Result;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Input\DescribeLogStreamsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\LogStream;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<LogStream>
 */
class DescribeLogStreamsResponse extends Result implements \IteratorAggregate
{
    /**
     * The log streams.
     */
    private $logStreams;

    private $nextToken;

    /**
     * Iterates over logStreams.
     *
     * @return \Traversable<LogStream>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getLogStreams();
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<LogStream>
     */
    public function getLogStreams(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->logStreams;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchLogsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeLogStreamsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->describeLogStreams($input));
            } else {
                $nextPage = null;
            }

            yield from $page->logStreams;

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

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->logStreams = empty($data['logStreams']) ? [] : $this->populateResultLogStreams($data['logStreams']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    /**
     * @return LogStream[]
     */
    private function populateResultLogStreams(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new LogStream([
                'logStreamName' => isset($item['logStreamName']) ? (string) $item['logStreamName'] : null,
                'creationTime' => isset($item['creationTime']) ? (string) $item['creationTime'] : null,
                'firstEventTimestamp' => isset($item['firstEventTimestamp']) ? (string) $item['firstEventTimestamp'] : null,
                'lastEventTimestamp' => isset($item['lastEventTimestamp']) ? (string) $item['lastEventTimestamp'] : null,
                'lastIngestionTime' => isset($item['lastIngestionTime']) ? (string) $item['lastIngestionTime'] : null,
                'uploadSequenceToken' => isset($item['uploadSequenceToken']) ? (string) $item['uploadSequenceToken'] : null,
                'arn' => isset($item['arn']) ? (string) $item['arn'] : null,
                'storedBytes' => isset($item['storedBytes']) ? (string) $item['storedBytes'] : null,
            ]);
        }

        return $items;
    }
}
