<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\ValueObject\StackEvent;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The output for a DescribeStackEvents action.
 *
 * @implements \IteratorAggregate<StackEvent>
 */
class DescribeStackEventsOutput extends Result implements \IteratorAggregate
{
    /**
     * A list of `StackEvents` structures.
     */
    private $stackEvents;

    /**
     * If the output exceeds 1 MB in size, a string that identifies the next page of events. If no additional page exists,
     * this value is null.
     */
    private $nextToken;

    /**
     * Iterates over StackEvents.
     *
     * @return \Traversable<StackEvent>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getStackEvents();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
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
            yield from $this->stackEvents;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudFormationClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeStackEventsInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->describeStackEvents($input));
            } else {
                $nextPage = null;
            }

            yield from $page->stackEvents;

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

        $this->stackEvents = !$data->StackEvents ? [] : $this->populateResultStackEvents($data->StackEvents);
        $this->nextToken = ($v = $data->NextToken) ? (string) $v : null;
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
