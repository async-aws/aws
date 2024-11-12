<?php

namespace AsyncAws\CloudWatch\Result;

use AsyncAws\CloudWatch\CloudWatchClient;
use AsyncAws\CloudWatch\Input\ListMetricsInput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\CloudWatch\ValueObject\Metric;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<Metric|string>
 */
class ListMetricsOutput extends Result implements \IteratorAggregate
{
    /**
     * The metrics that match your request.
     *
     * @var Metric[]
     */
    private $metrics;

    /**
     * The token that marks the start of the next batch of returned results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * If you are using this operation in a monitoring account, this array contains the account IDs of the source accounts
     * where the metrics in the returned data are from.
     *
     * This field is a 1:1 mapping between each metric that is returned and the ID of the owning account.
     *
     * @var string[]
     */
    private $owningAccounts;

    /**
     * Iterates over Metrics and OwningAccounts.
     *
     * @return \Traversable<Metric|string>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof CloudWatchClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMetricsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listMetrics($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getMetrics(true);
            yield from $page->getOwningAccounts(true);

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
     * @return iterable<Metric>
     */
    public function getMetrics(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->metrics;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMetricsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listMetrics($input));
            } else {
                $nextPage = null;
            }

            yield from $page->metrics;

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
    public function getOwningAccounts(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->owningAccounts;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMetricsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listMetrics($input));
            } else {
                $nextPage = null;
            }

            yield from $page->owningAccounts;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListMetricsResult;

        $this->metrics = (0 === ($v = $data->Metrics)->count()) ? [] : $this->populateResultMetrics($v);
        $this->nextToken = (null !== $v = $data->NextToken[0]) ? (string) $v : null;
        $this->owningAccounts = (0 === ($v = $data->OwningAccounts)->count()) ? [] : $this->populateResultOwningAccounts($v);
    }

    private function populateResultDimension(\SimpleXMLElement $xml): Dimension
    {
        return new Dimension([
            'Name' => (string) $xml->Name,
            'Value' => (string) $xml->Value,
        ]);
    }

    /**
     * @return Dimension[]
     */
    private function populateResultDimensions(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultDimension($item);
        }

        return $items;
    }

    private function populateResultMetric(\SimpleXMLElement $xml): Metric
    {
        return new Metric([
            'Namespace' => (null !== $v = $xml->Namespace[0]) ? (string) $v : null,
            'MetricName' => (null !== $v = $xml->MetricName[0]) ? (string) $v : null,
            'Dimensions' => (0 === ($v = $xml->Dimensions)->count()) ? null : $this->populateResultDimensions($v),
        ]);
    }

    /**
     * @return Metric[]
     */
    private function populateResultMetrics(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultMetric($item);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultOwningAccounts(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }
}
