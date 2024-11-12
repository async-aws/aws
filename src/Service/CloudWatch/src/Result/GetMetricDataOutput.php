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
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->GetMetricDataResult;

        $this->metricDataResults = (0 === ($v = $data->MetricDataResults)->count()) ? [] : $this->populateResultMetricDataResults($v);
        $this->nextToken = (null !== $v = $data->NextToken[0]) ? (string) $v : null;
        $this->messages = (0 === ($v = $data->Messages)->count()) ? [] : $this->populateResultMetricDataResultMessages($v);
    }

    /**
     * @return float[]
     */
    private function populateResultDatapointValues(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = (float) (string) $item;
        }

        return $items;
    }

    private function populateResultMessageData(\SimpleXMLElement $xml): MessageData
    {
        return new MessageData([
            'Code' => (null !== $v = $xml->Code[0]) ? (string) $v : null,
            'Value' => (null !== $v = $xml->Value[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultMetricDataResult(\SimpleXMLElement $xml): MetricDataResult
    {
        return new MetricDataResult([
            'Id' => (null !== $v = $xml->Id[0]) ? (string) $v : null,
            'Label' => (null !== $v = $xml->Label[0]) ? (string) $v : null,
            'Timestamps' => (0 === ($v = $xml->Timestamps)->count()) ? null : $this->populateResultTimestamps($v),
            'Values' => (0 === ($v = $xml->Values)->count()) ? null : $this->populateResultDatapointValues($v),
            'StatusCode' => (null !== $v = $xml->StatusCode[0]) ? (string) $v : null,
            'Messages' => (0 === ($v = $xml->Messages)->count()) ? null : $this->populateResultMetricDataResultMessages($v),
        ]);
    }

    /**
     * @return MessageData[]
     */
    private function populateResultMetricDataResultMessages(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultMessageData($item);
        }

        return $items;
    }

    /**
     * @return MetricDataResult[]
     */
    private function populateResultMetricDataResults(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultMetricDataResult($item);
        }

        return $items;
    }

    /**
     * @return \DateTimeImmutable[]
     */
    private function populateResultTimestamps(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new \DateTimeImmutable((string) $item);
        }

        return $items;
    }
}
