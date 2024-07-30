<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Enum\MetricsName;
use AsyncAws\Kinesis\Input\DescribeStreamInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\ValueObject\EnhancedMetrics;
use AsyncAws\Kinesis\ValueObject\HashKeyRange;
use AsyncAws\Kinesis\ValueObject\SequenceNumberRange;
use AsyncAws\Kinesis\ValueObject\Shard;
use AsyncAws\Kinesis\ValueObject\StreamDescription;
use AsyncAws\Kinesis\ValueObject\StreamModeDetails;

/**
 * Represents the output for `DescribeStream`.
 *
 * @implements \IteratorAggregate<Shard>
 */
class DescribeStreamOutput extends Result implements \IteratorAggregate
{
    /**
     * The current status of the stream, the stream Amazon Resource Name (ARN), an array of shard objects that comprise the
     * stream, and whether there are more shards available.
     *
     * @var StreamDescription
     */
    private $streamDescription;

    /**
     * Iterates over Shards.
     *
     * @return \Traversable<Shard>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof KinesisClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeStreamInput) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            $page = $page->streamDescription;
            if ($page->getHasMoreShards()) {
                $input->setExclusiveStartShardId(\array_slice($page->getShards(), -1)[0]->getShardId());

                $this->registerPrefetch($nextPage = $client->describeStream($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getShards();

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getStreamDescription(): StreamDescription
    {
        $this->initialize();

        return $this->streamDescription;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->streamDescription = $this->populateResultStreamDescription($data['StreamDescription']);
    }

    private function populateResultEnhancedMetrics(array $json): EnhancedMetrics
    {
        return new EnhancedMetrics([
            'ShardLevelMetrics' => !isset($json['ShardLevelMetrics']) ? null : $this->populateResultMetricsNameList($json['ShardLevelMetrics']),
        ]);
    }

    /**
     * @return EnhancedMetrics[]
     */
    private function populateResultEnhancedMonitoringList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultEnhancedMetrics($item);
        }

        return $items;
    }

    private function populateResultHashKeyRange(array $json): HashKeyRange
    {
        return new HashKeyRange([
            'StartingHashKey' => (string) $json['StartingHashKey'],
            'EndingHashKey' => (string) $json['EndingHashKey'],
        ]);
    }

    /**
     * @return list<MetricsName::*>
     */
    private function populateResultMetricsNameList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    private function populateResultSequenceNumberRange(array $json): SequenceNumberRange
    {
        return new SequenceNumberRange([
            'StartingSequenceNumber' => (string) $json['StartingSequenceNumber'],
            'EndingSequenceNumber' => isset($json['EndingSequenceNumber']) ? (string) $json['EndingSequenceNumber'] : null,
        ]);
    }

    private function populateResultShard(array $json): Shard
    {
        return new Shard([
            'ShardId' => (string) $json['ShardId'],
            'ParentShardId' => isset($json['ParentShardId']) ? (string) $json['ParentShardId'] : null,
            'AdjacentParentShardId' => isset($json['AdjacentParentShardId']) ? (string) $json['AdjacentParentShardId'] : null,
            'HashKeyRange' => $this->populateResultHashKeyRange($json['HashKeyRange']),
            'SequenceNumberRange' => $this->populateResultSequenceNumberRange($json['SequenceNumberRange']),
        ]);
    }

    /**
     * @return Shard[]
     */
    private function populateResultShardList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultShard($item);
        }

        return $items;
    }

    private function populateResultStreamDescription(array $json): StreamDescription
    {
        return new StreamDescription([
            'StreamName' => (string) $json['StreamName'],
            'StreamARN' => (string) $json['StreamARN'],
            'StreamStatus' => (string) $json['StreamStatus'],
            'StreamModeDetails' => empty($json['StreamModeDetails']) ? null : $this->populateResultStreamModeDetails($json['StreamModeDetails']),
            'Shards' => $this->populateResultShardList($json['Shards']),
            'HasMoreShards' => filter_var($json['HasMoreShards'], \FILTER_VALIDATE_BOOLEAN),
            'RetentionPeriodHours' => (int) $json['RetentionPeriodHours'],
            'StreamCreationTimestamp' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['StreamCreationTimestamp'])),
            'EnhancedMonitoring' => $this->populateResultEnhancedMonitoringList($json['EnhancedMonitoring']),
            'EncryptionType' => isset($json['EncryptionType']) ? (string) $json['EncryptionType'] : null,
            'KeyId' => isset($json['KeyId']) ? (string) $json['KeyId'] : null,
        ]);
    }

    private function populateResultStreamModeDetails(array $json): StreamModeDetails
    {
        return new StreamModeDetails([
            'StreamMode' => (string) $json['StreamMode'],
        ]);
    }
}
