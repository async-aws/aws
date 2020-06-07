<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\ValueObject\StackEvent;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<StackEvent>
 */
class DescribeStackEventsOutput extends Result implements \IteratorAggregate
{
    /**
     * A list of `StackEvents` structures.
     */
    private $StackEvents = [];

    /**
     * If the output exceeds 1 MB in size, a string that identifies the next page of events. If no additional page exists,
     * this value is null.
     */
    private $NextToken;

    /**
     * Iterates over StackEvents.
     *
     * @return \Traversable<StackEvent>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof CloudFormationClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeStackEventsInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->DescribeStackEvents($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getStackEvents(true);

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

        return $this->NextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<StackEvent>
     */
    public function getStackEvents(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->StackEvents;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudFormationClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeStackEventsInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->DescribeStackEvents($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getStackEvents(true);

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
        $data = $data->DescribeStackEventsResult;

        $this->StackEvents = !$data->StackEvents ? [] : $this->populateResultStackEvents($data->StackEvents);
        $this->NextToken = ($v = $data->NextToken) ? (string) $v : null;
    }

    /**
     * @return StackEvent[]
     */
    private function populateResultStackEvents(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new StackEvent([
                'StackId' => (string) $item->StackId,
                'EventId' => (string) $item->EventId,
                'StackName' => (string) $item->StackName,
                'LogicalResourceId' => ($v = $item->LogicalResourceId) ? (string) $v : null,
                'PhysicalResourceId' => ($v = $item->PhysicalResourceId) ? (string) $v : null,
                'ResourceType' => ($v = $item->ResourceType) ? (string) $v : null,
                'Timestamp' => new \DateTimeImmutable((string) $item->Timestamp),
                'ResourceStatus' => ($v = $item->ResourceStatus) ? (string) $v : null,
                'ResourceStatusReason' => ($v = $item->ResourceStatusReason) ? (string) $v : null,
                'ResourceProperties' => ($v = $item->ResourceProperties) ? (string) $v : null,
                'ClientRequestToken' => ($v = $item->ClientRequestToken) ? (string) $v : null,
            ]);
        }

        return $items;
    }
}
