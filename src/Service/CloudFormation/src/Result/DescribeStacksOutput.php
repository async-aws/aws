<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\ValueObject\Output;
use AsyncAws\CloudFormation\ValueObject\Parameter;
use AsyncAws\CloudFormation\ValueObject\RollbackConfiguration;
use AsyncAws\CloudFormation\ValueObject\RollbackTrigger;
use AsyncAws\CloudFormation\ValueObject\Stack;
use AsyncAws\CloudFormation\ValueObject\StackDriftInformation;
use AsyncAws\CloudFormation\ValueObject\Tag;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DescribeStacksOutput extends Result
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

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->NextToken;
    }

    /**
     * @return Stack[]
     */
    public function getStacks(): array
    {
        $this->initialize();

        return $this->Stacks;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
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
