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
 * @implements \IteratorAggregate<Metric>
 */
class ListMetricsOutput extends Result implements \IteratorAggregate
{
    /**
     * The metrics that match your request.
     */
    private $metrics;

    /**
     * The token that marks the start of the next batch of returned results.
     */
    private $nextToken;

    /**
     * Iterates over Metrics.
     *
     * @return \Traversable<Metric>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getMetrics();
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
            if ($page->nextToken) {
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

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ListMetricsResult;

        $this->metrics = !$data->Metrics ? [] : $this->populateResultMetrics($data->Metrics);
        $this->nextToken = ($v = $data->NextToken) ? (string) $v : null;
    }

    /**
     * @return Dimension[]
     */
    private function populateResultDimensions(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new Dimension([
                'Name' => (string) $item->Name,
                'Value' => (string) $item->Value,
            ]);
        }

        return $items;
    }

    /**
     * @return Metric[]
     */
    private function populateResultMetrics(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new Metric([
                'Namespace' => ($v = $item->Namespace) ? (string) $v : null,
                'MetricName' => ($v = $item->MetricName) ? (string) $v : null,
                'Dimensions' => !$item->Dimensions ? null : $this->populateResultDimensions($item->Dimensions),
            ]);
        }

        return $items;
    }
}
