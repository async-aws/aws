<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

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
     * @var self[]
     */
    private $prefetch = [];

    public function __destruct()
    {
        while (!empty($this->prefetch)) {
            array_shift($this->prefetch)->cancel();
        }

        parent::__destruct();
    }

    /**
     * Iterates over StackEvents.
     *
     * @return \Traversable<StackEvent>
     */
    public function getIterator(): \Traversable
    {
        if (!$this->awsClient instanceof CloudFormationClient) {
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

                $nextPage = $this->awsClient->DescribeStackEvents($input);
                $this->prefetch[spl_object_hash($nextPage)] = $nextPage;
            } else {
                $nextPage = null;
            }

            yield from $page->getStackEvents(true);

            if (null === $nextPage) {
                break;
            }

            unset($this->prefetch[spl_object_hash($nextPage)]);
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

        if (!$this->awsClient instanceof CloudFormationClient) {
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

                $nextPage = $this->awsClient->DescribeStackEvents($input);
                $this->prefetch[spl_object_hash($nextPage)] = $nextPage;
            } else {
                $nextPage = null;
            }

            yield from $page->getStackEvents(true);

            if (null === $nextPage) {
                break;
            }

            unset($this->prefetch[spl_object_hash($nextPage)]);
            $page = $nextPage;
        }
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->DescribeStackEventsResult;

        $this->StackEvents = (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml as $item) {
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
        })($data->StackEvents);
        $this->NextToken = ($v = $data->NextToken) ? (string) $v : null;
    }
}
