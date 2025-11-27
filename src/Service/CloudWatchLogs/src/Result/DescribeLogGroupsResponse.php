<?php

namespace AsyncAws\CloudWatchLogs\Result;

use AsyncAws\CloudWatchLogs\CloudWatchLogsClient;
use AsyncAws\CloudWatchLogs\Enum\DataProtectionStatus;
use AsyncAws\CloudWatchLogs\Enum\InheritedProperty;
use AsyncAws\CloudWatchLogs\Enum\LogGroupClass;
use AsyncAws\CloudWatchLogs\Input\DescribeLogGroupsRequest;
use AsyncAws\CloudWatchLogs\ValueObject\LogGroup;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<LogGroup>
 */
class DescribeLogGroupsResponse extends Result implements \IteratorAggregate
{
    /**
     * An array of structures, where each structure contains the information about one log group.
     *
     * @var LogGroup[]
     */
    private $logGroups;

    /**
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over logGroups.
     *
     * @return \Traversable<LogGroup>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getLogGroups();
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<LogGroup>
     */
    public function getLogGroups(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->logGroups;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof CloudWatchLogsClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeLogGroupsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->describeLogGroups($input));
            } else {
                $nextPage = null;
            }

            yield from $page->logGroups;

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
        $data = $response->toArray();

        $this->logGroups = empty($data['logGroups']) ? [] : $this->populateResultLogGroups($data['logGroups']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    /**
     * @return list<InheritedProperty::*>
     */
    private function populateResultInheritedProperties(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                if (!InheritedProperty::exists($a)) {
                    $a = InheritedProperty::UNKNOWN_TO_SDK;
                }
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultLogGroup(array $json): LogGroup
    {
        return new LogGroup([
            'logGroupName' => isset($json['logGroupName']) ? (string) $json['logGroupName'] : null,
            'creationTime' => isset($json['creationTime']) ? (int) $json['creationTime'] : null,
            'retentionInDays' => isset($json['retentionInDays']) ? (int) $json['retentionInDays'] : null,
            'metricFilterCount' => isset($json['metricFilterCount']) ? (int) $json['metricFilterCount'] : null,
            'arn' => isset($json['arn']) ? (string) $json['arn'] : null,
            'storedBytes' => isset($json['storedBytes']) ? (int) $json['storedBytes'] : null,
            'kmsKeyId' => isset($json['kmsKeyId']) ? (string) $json['kmsKeyId'] : null,
            'dataProtectionStatus' => isset($json['dataProtectionStatus']) ? (!DataProtectionStatus::exists((string) $json['dataProtectionStatus']) ? DataProtectionStatus::UNKNOWN_TO_SDK : (string) $json['dataProtectionStatus']) : null,
            'inheritedProperties' => !isset($json['inheritedProperties']) ? null : $this->populateResultInheritedProperties($json['inheritedProperties']),
            'logGroupClass' => isset($json['logGroupClass']) ? (!LogGroupClass::exists((string) $json['logGroupClass']) ? LogGroupClass::UNKNOWN_TO_SDK : (string) $json['logGroupClass']) : null,
            'logGroupArn' => isset($json['logGroupArn']) ? (string) $json['logGroupArn'] : null,
            'deletionProtectionEnabled' => isset($json['deletionProtectionEnabled']) ? filter_var($json['deletionProtectionEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }

    /**
     * @return LogGroup[]
     */
    private function populateResultLogGroups(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultLogGroup($item);
        }

        return $items;
    }
}
