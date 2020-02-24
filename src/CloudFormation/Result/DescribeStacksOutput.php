<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DescribeStacksOutput extends Result implements \IteratorAggregate
{
    /**
     * A list of stack structures.
     */
    private $Stacks = [];

    /**
     * If the output exceeds 1 MB in size, a string that identifies the next page of stacks. If no additional page exists,
     * this value is null.
     */
    private $NextToken;

    /**
     * Iterates over Stacks.
     *
     * @return \Traversable<Stack>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof CloudFormationClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeStacksInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->DescribeStacks($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getStacks(true);

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
     * @return iterable<Stack>
     */
    public function getStacks(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->Stacks;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudFormationClient) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeStacksInput) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getNextToken()) {
                $input->setNextToken($page->getNextToken());

                $this->registerPrefetch($nextPage = $client->DescribeStacks($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getStacks(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->DescribeStacksResult;

        $this->Stacks = !$data->Stacks ? [] : (function (\SimpleXMLElement $xml): array {
            $items = [];
            foreach ($xml->member as $item) {
                $items[] = new Stack([
                    'StackId' => ($v = $item->StackId) ? (string) $v : null,
                    'StackName' => (string) $item->StackName,
                    'ChangeSetId' => ($v = $item->ChangeSetId) ? (string) $v : null,
                    'Description' => ($v = $item->Description) ? (string) $v : null,
                    'Parameters' => !$item->Parameters ? [] : (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml->member as $item) {
                            $items[] = new Parameter([
                                'ParameterKey' => ($v = $item->ParameterKey) ? (string) $v : null,
                                'ParameterValue' => ($v = $item->ParameterValue) ? (string) $v : null,
                                'UsePreviousValue' => ($v = $item->UsePreviousValue) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                                'ResolvedValue' => ($v = $item->ResolvedValue) ? (string) $v : null,
                            ]);
                        }

                        return $items;
                    })($item->Parameters),
                    'CreationTime' => new \DateTimeImmutable((string) $item->CreationTime),
                    'DeletionTime' => ($v = $item->DeletionTime) ? new \DateTimeImmutable((string) $v) : null,
                    'LastUpdatedTime' => ($v = $item->LastUpdatedTime) ? new \DateTimeImmutable((string) $v) : null,
                    'RollbackConfiguration' => !$item->RollbackConfiguration ? null : new RollbackConfiguration([
                        'RollbackTriggers' => !$item->RollbackConfiguration->RollbackTriggers ? [] : (function (\SimpleXMLElement $xml): array {
                            $items = [];
                            foreach ($xml->member as $item) {
                                $items[] = new RollbackTrigger([
                                    'Arn' => (string) $item->Arn,
                                    'Type' => (string) $item->Type,
                                ]);
                            }

                            return $items;
                        })($item->RollbackConfiguration->RollbackTriggers),
                        'MonitoringTimeInMinutes' => ($v = $item->RollbackConfiguration->MonitoringTimeInMinutes) ? (int) (string) $v : null,
                    ]),
                    'StackStatus' => (string) $item->StackStatus,
                    'StackStatusReason' => ($v = $item->StackStatusReason) ? (string) $v : null,
                    'DisableRollback' => ($v = $item->DisableRollback) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                    'NotificationARNs' => !$item->NotificationARNs ? [] : (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml->member as $item) {
                            $a = ($v = $item) ? (string) $v : null;
                            if (null !== $a) {
                                $items[] = $a;
                            }
                        }

                        return $items;
                    })($item->NotificationARNs),
                    'TimeoutInMinutes' => ($v = $item->TimeoutInMinutes) ? (int) (string) $v : null,
                    'Capabilities' => !$item->Capabilities ? [] : (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml->member as $item) {
                            $a = ($v = $item) ? (string) $v : null;
                            if (null !== $a) {
                                $items[] = $a;
                            }
                        }

                        return $items;
                    })($item->Capabilities),
                    'Outputs' => !$item->Outputs ? [] : (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml->member as $item) {
                            $items[] = new Output([
                                'OutputKey' => ($v = $item->OutputKey) ? (string) $v : null,
                                'OutputValue' => ($v = $item->OutputValue) ? (string) $v : null,
                                'Description' => ($v = $item->Description) ? (string) $v : null,
                                'ExportName' => ($v = $item->ExportName) ? (string) $v : null,
                            ]);
                        }

                        return $items;
                    })($item->Outputs),
                    'RoleARN' => ($v = $item->RoleARN) ? (string) $v : null,
                    'Tags' => !$item->Tags ? [] : (function (\SimpleXMLElement $xml): array {
                        $items = [];
                        foreach ($xml->member as $item) {
                            $items[] = new Tag([
                                'Key' => (string) $item->Key,
                                'Value' => (string) $item->Value,
                            ]);
                        }

                        return $items;
                    })($item->Tags),
                    'EnableTerminationProtection' => ($v = $item->EnableTerminationProtection) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                    'ParentId' => ($v = $item->ParentId) ? (string) $v : null,
                    'RootId' => ($v = $item->RootId) ? (string) $v : null,
                    'DriftInformation' => !$item->DriftInformation ? null : new StackDriftInformation([
                        'StackDriftStatus' => (string) $item->DriftInformation->StackDriftStatus,
                        'LastCheckTimestamp' => ($v = $item->DriftInformation->LastCheckTimestamp) ? new \DateTimeImmutable((string) $v) : null,
                    ]),
                ]);
            }

            return $items;
        })($data->Stacks);
        $this->NextToken = ($v = $data->NextToken) ? (string) $v : null;
    }
}
