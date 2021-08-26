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
     */
    private $metricDataResults = [];

    /**
     * A token that marks the next batch of returned results.
     */
    private $nextToken;

    /**
     * Contains a message about this `GetMetricData` operation, if the operation results in such a message. An example of a
     * message that might be returned is `Maximum number of allowed metrics exceeded`. If there is a message, as much of the
     * operation as possible is still executed.
     */
    private $messages = [];

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
            if ($page->nextToken) {
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
            if ($page->nextToken) {
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
            if ($page->nextToken) {
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

        $this->metricDataResults = !$data->MetricDataResults ? [] : $this->populateResultMetricDataResults($data->MetricDataResults);
        $this->nextToken = ($v = $data->NextToken) ? (string) $v : null;
        $this->messages = !$data->Messages ? [] : $this->populateResultMetricDataResultMessages($data->Messages);
    }

    /**
     * @return float[]
     */
    private function populateResultDatapointValues(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $a = ($v = $item) ? (float) (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return MessageData[]
     */
    private function populateResultMetricDataResultMessages(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new MessageData([
                'Code' => ($v = $item->Code) ? (string) $v : null,
                'Value' => ($v = $item->Value) ? (string) $v : null,
            ]);
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
            $items[] = new MetricDataResult([
                'Id' => ($v = $item->Id) ? (string) $v : null,
                'Label' => ($v = $item->Label) ? (string) $v : null,
                'Timestamps' => !$item->Timestamps ? [] : $this->populateResultTimestamps($item->Timestamps),
                'Values' => !$item->Values ? [] : $this->populateResultDatapointValues($item->Values),
                'StatusCode' => ($v = $item->StatusCode) ? (string) $v : null,
                'Messages' => !$item->Messages ? [] : $this->populateResultMetricDataResultMessages($item->Messages),
            ]);
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
            $a = ($v = $item) ? new \DateTimeImmutable((string) $v) : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }
}
