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
     *
     * @var StackEvent[]
     */
    private $stackEvents;

    /**
     * If the output exceeds 1 MB in size, a string that identifies the next page of events. If no additional page exists,
     * this value is null.
     *
     * @var string|null
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
            if (null !== $page->nextToken) {
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

        $this->stackEvents = (0 === ($v = $data->StackEvents)->count()) ? [] : $this->populateResultStackEvents($v);
        $this->nextToken = (null !== $v = $data->NextToken[0]) ? (string) $v : null;
    }

    private function populateResultStackEvent(\SimpleXMLElement $xml): StackEvent
    {
        return new StackEvent([
            'StackId' => (string) $xml->StackId,
            'EventId' => (string) $xml->EventId,
            'StackName' => (string) $xml->StackName,
            'OperationId' => (null !== $v = $xml->OperationId[0]) ? (string) $v : null,
            'LogicalResourceId' => (null !== $v = $xml->LogicalResourceId[0]) ? (string) $v : null,
            'PhysicalResourceId' => (null !== $v = $xml->PhysicalResourceId[0]) ? (string) $v : null,
            'ResourceType' => (null !== $v = $xml->ResourceType[0]) ? (string) $v : null,
            'Timestamp' => new \DateTimeImmutable((string) $xml->Timestamp),
            'ResourceStatus' => (null !== $v = $xml->ResourceStatus[0]) ? (string) $v : null,
            'ResourceStatusReason' => (null !== $v = $xml->ResourceStatusReason[0]) ? (string) $v : null,
            'ResourceProperties' => (null !== $v = $xml->ResourceProperties[0]) ? (string) $v : null,
            'ClientRequestToken' => (null !== $v = $xml->ClientRequestToken[0]) ? (string) $v : null,
            'HookType' => (null !== $v = $xml->HookType[0]) ? (string) $v : null,
            'HookStatus' => (null !== $v = $xml->HookStatus[0]) ? (string) $v : null,
            'HookStatusReason' => (null !== $v = $xml->HookStatusReason[0]) ? (string) $v : null,
            'HookInvocationPoint' => (null !== $v = $xml->HookInvocationPoint[0]) ? (string) $v : null,
            'HookInvocationId' => (null !== $v = $xml->HookInvocationId[0]) ? (string) $v : null,
            'HookFailureMode' => (null !== $v = $xml->HookFailureMode[0]) ? (string) $v : null,
            'DetailedStatus' => (null !== $v = $xml->DetailedStatus[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return StackEvent[]
     */
    private function populateResultStackEvents(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultStackEvent($item);
        }

        return $items;
    }
}
