<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Enum\Capability;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\CloudFormation\ValueObject\Output;
use AsyncAws\CloudFormation\ValueObject\Parameter;
use AsyncAws\CloudFormation\ValueObject\RollbackConfiguration;
use AsyncAws\CloudFormation\ValueObject\RollbackTrigger;
use AsyncAws\CloudFormation\ValueObject\Stack;
use AsyncAws\CloudFormation\ValueObject\StackDriftInformation;
use AsyncAws\CloudFormation\ValueObject\Tag;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<Stack>
 */
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

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->DescribeStacksResult;

        $this->Stacks = !$data->Stacks ? [] : $this->populateResultStacks($data->Stacks);
        $this->NextToken = ($v = $data->NextToken) ? (string) $v : null;
    }

    /**
     * @return list<Capability::*>
     */
    private function populateResultCapabilities(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultNotificationARNs(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return Output[]
     */
    private function populateResultOutputs(\SimpleXMLElement $xml): array
    {
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
    }

    /**
     * @return Parameter[]
     */
    private function populateResultParameters(\SimpleXMLElement $xml): array
    {
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
    }

    /**
     * @return RollbackTrigger[]
     */
    private function populateResultRollbackTriggers(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new RollbackTrigger([
                'Arn' => (string) $item->Arn,
                'Type' => (string) $item->Type,
            ]);
        }

        return $items;
    }

    /**
     * @return Stack[]
     */
    private function populateResultStacks(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new Stack([
                'StackId' => ($v = $item->StackId) ? (string) $v : null,
                'StackName' => (string) $item->StackName,
                'ChangeSetId' => ($v = $item->ChangeSetId) ? (string) $v : null,
                'Description' => ($v = $item->Description) ? (string) $v : null,
                'Parameters' => !$item->Parameters ? [] : $this->populateResultParameters($item->Parameters),
                'CreationTime' => new \DateTimeImmutable((string) $item->CreationTime),
                'DeletionTime' => ($v = $item->DeletionTime) ? new \DateTimeImmutable((string) $v) : null,
                'LastUpdatedTime' => ($v = $item->LastUpdatedTime) ? new \DateTimeImmutable((string) $v) : null,
                'RollbackConfiguration' => !$item->RollbackConfiguration ? null : new RollbackConfiguration([
                    'RollbackTriggers' => !$item->RollbackConfiguration->RollbackTriggers ? [] : $this->populateResultRollbackTriggers($item->RollbackConfiguration->RollbackTriggers),
                    'MonitoringTimeInMinutes' => ($v = $item->RollbackConfiguration->MonitoringTimeInMinutes) ? (int) (string) $v : null,
                ]),
                'StackStatus' => (string) $item->StackStatus,
                'StackStatusReason' => ($v = $item->StackStatusReason) ? (string) $v : null,
                'DisableRollback' => ($v = $item->DisableRollback) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'NotificationARNs' => !$item->NotificationARNs ? [] : $this->populateResultNotificationARNs($item->NotificationARNs),
                'TimeoutInMinutes' => ($v = $item->TimeoutInMinutes) ? (int) (string) $v : null,
                'Capabilities' => !$item->Capabilities ? [] : $this->populateResultCapabilities($item->Capabilities),
                'Outputs' => !$item->Outputs ? [] : $this->populateResultOutputs($item->Outputs),
                'RoleARN' => ($v = $item->RoleARN) ? (string) $v : null,
                'Tags' => !$item->Tags ? [] : $this->populateResultTags($item->Tags),
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
    }

    /**
     * @return Tag[]
     */
    private function populateResultTags(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new Tag([
                'Key' => (string) $item->Key,
                'Value' => (string) $item->Value,
            ]);
        }

        return $items;
    }
}
