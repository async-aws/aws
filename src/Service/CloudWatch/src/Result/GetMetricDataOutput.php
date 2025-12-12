<?php

namespace AsyncAws\CloudWatch\Result;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\GetMetricDataInput;
use AsyncAws\CloudWatch\ValueObject\MessageData;
use AsyncAws\CloudWatch\ValueObject\MetricDataResult;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<MetricDataResult|MessageData>
 */
class GetMetricDataOutput extends Result implements \IteratorAggregate
{
    /**
     * The metrics that are returned, including the metric name, namespace, and dimensions.
     *
     * @var MetricDataResult[]
     */
    private $metricDataResults;

    /**
     * A token that marks the next batch of returned results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Contains a message about this `GetMetricData` operation, if the operation results in such a message. An example of a
     * message that might be returned is `Maximum number of allowed metrics exceeded`. If there is a message, as much of the
     * operation as possible is still executed.
     *
     * A message appears here only if it is related to the global `GetMetricData` operation. Any message about a specific
     * metric returned by the operation appears in the `MetricDataResult` object returned for that metric.
     *
     * @var MessageData[]
     */
    private $messages;

    /**
     * Iterates over MetricDataResults and Messages.
     *
     * @return \Traversable<MetricDataResult|MessageData>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof CloudWatchClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof GetMetricDataInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->getMetricData($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getMetricDataResults(true);
            yield from $page->getMessages(true);

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
     * @return iterable<MessageData>
     */
    public function getMessages(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->messages;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof GetMetricDataInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->getMetricData($input));
            } else {
                $nextPage = null;
            }

            yield from $page->messages;

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
     * @return iterable<MetricDataResult>
     */
    public function getMetricDataResults(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->metricDataResults;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof GetMetricDataInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->getMetricData($input));
            } else {
                $nextPage = null;
            }

            yield from $page->metricDataResults;

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

        $this->metricDataResults = empty($data['MetricDataResults']) ? [] : $this->populateResultMetricDataResults($data['MetricDataResults']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
        $this->messages = empty($data['Messages']) ? [] : $this->populateResultMetricDataResultMessages($data['Messages']);
    }

    /**
     * @return float[]
     */
    private function populateResultDatapointValues(array $json): array
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

    private function populateResultMessageData(array $json): MessageData
    {
        return new MessageData([
            'Code' => isset($json['Code']) ? (string) $json['Code'] : null,
            'Value' => isset($json['Value']) ? (string) $json['Value'] : null,
        ]);
    }

    private function populateResultMetricDataResult(array $json): MetricDataResult
    {
        return new MetricDataResult([
            'Id' => isset($json['Id']) ? (string) $json['Id'] : null,
            'Label' => isset($json['Label']) ? (string) $json['Label'] : null,
            'Timestamps' => !isset($json['Timestamps']) ? null : $this->populateResultTimestamps($json['Timestamps']),
            'Values' => !isset($json['Values']) ? null : $this->populateResultDatapointValues($json['Values']),
            'StatusCode' => isset($json['StatusCode']) ? (string) $json['StatusCode'] : null,
            'Messages' => !isset($json['Messages']) ? null : $this->populateResultMetricDataResultMessages($json['Messages']),
        ]);
    }

    /**
     * @return MessageData[]
     */
    private function populateResultMetricDataResultMessages(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultMessageData($item);
        }

        return $items;
    }

    /**
     * @return MetricDataResult[]
     */
    private function populateResultMetricDataResults(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultMetricDataResult($item);
        }

        return $items;
    }

    /**
     * @return \DateTimeImmutable[]
     */
    private function populateResultTimestamps(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = (isset($item) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $item)))) ? $d : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
