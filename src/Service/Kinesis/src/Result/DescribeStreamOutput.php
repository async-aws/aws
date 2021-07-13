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

        $this->streamDescription = new StreamDescription([
            'StreamName' => (string) $data['StreamDescription']['StreamName'],
            'StreamARN' => (string) $data['StreamDescription']['StreamARN'],
            'StreamStatus' => (string) $data['StreamDescription']['StreamStatus'],
            'Shards' => $this->populateResultShardList($data['StreamDescription']['Shards']),
            'HasMoreShards' => filter_var($data['StreamDescription']['HasMoreShards'], \FILTER_VALIDATE_BOOLEAN),
            'RetentionPeriodHours' => (int) $data['StreamDescription']['RetentionPeriodHours'],
            'StreamCreationTimestamp' => /** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $data['StreamDescription']['StreamCreationTimestamp'])),
            'EnhancedMonitoring' => $this->populateResultEnhancedMonitoringList($data['StreamDescription']['EnhancedMonitoring']),
            'EncryptionType' => isset($data['StreamDescription']['EncryptionType']) ? (string) $data['StreamDescription']['EncryptionType'] : null,
            'KeyId' => isset($data['StreamDescription']['KeyId']) ? (string) $data['StreamDescription']['KeyId'] : null,
        ]);
    }

    /**
     * @return EnhancedMetrics[]
     */
    private function populateResultEnhancedMonitoringList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new EnhancedMetrics([
                'ShardLevelMetrics' => empty($item['ShardLevelMetrics']) ? [] : $this->populateResultMetricsNameList($item['ShardLevelMetrics']),
            ]);
        }

        return $items;
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

    /**
     * @return Shard[]
     */
    private function populateResultShardList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Shard([
                'ShardId' => (string) $item['ShardId'],
                'ParentShardId' => isset($item['ParentShardId']) ? (string) $item['ParentShardId'] : null,
                'AdjacentParentShardId' => isset($item['AdjacentParentShardId']) ? (string) $item['AdjacentParentShardId'] : null,
                'HashKeyRange' => new HashKeyRange([
                    'StartingHashKey' => (string) $item['HashKeyRange']['StartingHashKey'],
                    'EndingHashKey' => (string) $item['HashKeyRange']['EndingHashKey'],
                ]),
                'SequenceNumberRange' => new SequenceNumberRange([
                    'StartingSequenceNumber' => (string) $item['SequenceNumberRange']['StartingSequenceNumber'],
                    'EndingSequenceNumber' => isset($item['SequenceNumberRange']['EndingSequenceNumber']) ? (string) $item['SequenceNumberRange']['EndingSequenceNumber'] : null,
                ]),
            ]);
        }

        return $items;
    }
}
