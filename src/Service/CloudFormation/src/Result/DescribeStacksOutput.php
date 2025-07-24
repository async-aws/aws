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
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The output for a DescribeStacks action.
 *
 * @implements \IteratorAggregate<Stack>
 */
class DescribeStacksOutput extends Result implements \IteratorAggregate
{
    /**
     * A list of stack structures.
     *
     * @var Stack[]
     */
    private $stacks;

    /**
     * If the output exceeds 1 MB in size, a string that identifies the next page of stacks. If no additional page exists,
     * this value is null.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over Stacks.
     *
     * @return \Traversable<Stack>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getStacks();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
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
            yield from $this->stacks;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudFormationClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeStacksInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->describeStacks($input));
            } else {
                $nextPage = null;
            }

            yield from $page->stacks;

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

        $this->stacks = (0 === ($v = $data->Stacks)->count()) ? [] : $this->populateResultStacks($v);
        $this->nextToken = (null !== $v = $data->NextToken[0]) ? (string) $v : null;
    }

    /**
     * @return list<Capability::*|string>
     */
    private function populateResultCapabilities(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = (string) $item;
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
            $items[] = (string) $item;
        }

        return $items;
    }

    private function populateResultOutput(\SimpleXMLElement $xml): Output
    {
        return new Output([
            'OutputKey' => (null !== $v = $xml->OutputKey[0]) ? (string) $v : null,
            'OutputValue' => (null !== $v = $xml->OutputValue[0]) ? (string) $v : null,
            'Description' => (null !== $v = $xml->Description[0]) ? (string) $v : null,
            'ExportName' => (null !== $v = $xml->ExportName[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Output[]
     */
    private function populateResultOutputs(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultOutput($item);
        }

        return $items;
    }

    private function populateResultParameter(\SimpleXMLElement $xml): Parameter
    {
        return new Parameter([
            'ParameterKey' => (null !== $v = $xml->ParameterKey[0]) ? (string) $v : null,
            'ParameterValue' => (null !== $v = $xml->ParameterValue[0]) ? (string) $v : null,
            'UsePreviousValue' => (null !== $v = $xml->UsePreviousValue[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'ResolvedValue' => (null !== $v = $xml->ResolvedValue[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Parameter[]
     */
    private function populateResultParameters(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultParameter($item);
        }

        return $items;
    }

    private function populateResultRollbackConfiguration(\SimpleXMLElement $xml): RollbackConfiguration
    {
        return new RollbackConfiguration([
            'RollbackTriggers' => (0 === ($v = $xml->RollbackTriggers)->count()) ? null : $this->populateResultRollbackTriggers($v),
            'MonitoringTimeInMinutes' => (null !== $v = $xml->MonitoringTimeInMinutes[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultRollbackTrigger(\SimpleXMLElement $xml): RollbackTrigger
    {
        return new RollbackTrigger([
            'Arn' => (string) $xml->Arn,
            'Type' => (string) $xml->Type,
        ]);
    }

    /**
     * @return RollbackTrigger[]
     */
    private function populateResultRollbackTriggers(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultRollbackTrigger($item);
        }

        return $items;
    }

    private function populateResultStack(\SimpleXMLElement $xml): Stack
    {
        return new Stack([
            'StackId' => (null !== $v = $xml->StackId[0]) ? (string) $v : null,
            'StackName' => (string) $xml->StackName,
            'ChangeSetId' => (null !== $v = $xml->ChangeSetId[0]) ? (string) $v : null,
            'Description' => (null !== $v = $xml->Description[0]) ? (string) $v : null,
            'Parameters' => (0 === ($v = $xml->Parameters)->count()) ? null : $this->populateResultParameters($v),
            'CreationTime' => new \DateTimeImmutable((string) $xml->CreationTime),
            'DeletionTime' => (null !== $v = $xml->DeletionTime[0]) ? new \DateTimeImmutable((string) $v) : null,
            'LastUpdatedTime' => (null !== $v = $xml->LastUpdatedTime[0]) ? new \DateTimeImmutable((string) $v) : null,
            'RollbackConfiguration' => 0 === $xml->RollbackConfiguration->count() ? null : $this->populateResultRollbackConfiguration($xml->RollbackConfiguration),
            'StackStatus' => (string) $xml->StackStatus,
            'StackStatusReason' => (null !== $v = $xml->StackStatusReason[0]) ? (string) $v : null,
            'DisableRollback' => (null !== $v = $xml->DisableRollback[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'NotificationARNs' => (0 === ($v = $xml->NotificationARNs)->count()) ? null : $this->populateResultNotificationARNs($v),
            'TimeoutInMinutes' => (null !== $v = $xml->TimeoutInMinutes[0]) ? (int) (string) $v : null,
            'Capabilities' => (0 === ($v = $xml->Capabilities)->count()) ? null : $this->populateResultCapabilities($v),
            'Outputs' => (0 === ($v = $xml->Outputs)->count()) ? null : $this->populateResultOutputs($v),
            'RoleARN' => (null !== $v = $xml->RoleARN[0]) ? (string) $v : null,
            'Tags' => (0 === ($v = $xml->Tags)->count()) ? null : $this->populateResultTags($v),
            'EnableTerminationProtection' => (null !== $v = $xml->EnableTerminationProtection[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'ParentId' => (null !== $v = $xml->ParentId[0]) ? (string) $v : null,
            'RootId' => (null !== $v = $xml->RootId[0]) ? (string) $v : null,
            'DriftInformation' => 0 === $xml->DriftInformation->count() ? null : $this->populateResultStackDriftInformation($xml->DriftInformation),
            'RetainExceptOnCreate' => (null !== $v = $xml->RetainExceptOnCreate[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'DeletionMode' => (null !== $v = $xml->DeletionMode[0]) ? (string) $v : null,
            'DetailedStatus' => (null !== $v = $xml->DetailedStatus[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultStackDriftInformation(\SimpleXMLElement $xml): StackDriftInformation
    {
        return new StackDriftInformation([
            'StackDriftStatus' => (string) $xml->StackDriftStatus,
            'LastCheckTimestamp' => (null !== $v = $xml->LastCheckTimestamp[0]) ? new \DateTimeImmutable((string) $v) : null,
        ]);
    }

    /**
     * @return Stack[]
     */
    private function populateResultStacks(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultStack($item);
        }

        return $items;
    }

    private function populateResultTag(\SimpleXMLElement $xml): Tag
    {
        return new Tag([
            'Key' => (string) $xml->Key,
            'Value' => (string) $xml->Value,
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTags(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }
}
