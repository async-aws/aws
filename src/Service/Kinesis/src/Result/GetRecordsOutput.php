<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\ValueObject\ChildShard;
use AsyncAws\Kinesis\ValueObject\HashKeyRange;
use AsyncAws\Kinesis\ValueObject\Record;

/**
 * Represents the output for GetRecords.
 */
class GetRecordsOutput extends Result
{
    /**
     * The data records retrieved from the shard.
     */
    private $records;

    /**
     * The next position in the shard from which to start sequentially reading data records. If set to `null`, the shard has
     * been closed and the requested iterator does not return any more data.
     */
    private $nextShardIterator;

    /**
     * The number of milliseconds the GetRecords response is from the tip of the stream, indicating how far behind current
     * time the consumer is. A value of zero indicates that record processing is caught up, and there are no new records to
     * process at this moment.
     */
    private $millisBehindLatest;

    /**
     * The list of the current shard's child shards, returned in the `GetRecords` API's response only when the end of the
     * current shard is reached.
     */
    private $childShards;

    /**
     * @return ChildShard[]
     */
    public function getChildShards(): array
    {
        $this->initialize();

        return $this->childShards;
    }

    public function getMillisBehindLatest(): ?string
    {
        $this->initialize();

        return $this->millisBehindLatest;
    }

    public function getNextShardIterator(): ?string
    {
        $this->initialize();

        return $this->nextShardIterator;
    }

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        $this->initialize();

        return $this->records;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->records = $this->populateResultRecordList($data['Records']);
        $this->nextShardIterator = isset($data['NextShardIterator']) ? (string) $data['NextShardIterator'] : null;
        $this->millisBehindLatest = isset($data['MillisBehindLatest']) ? (string) $data['MillisBehindLatest'] : null;
        $this->childShards = empty($data['ChildShards']) ? [] : $this->populateResultChildShardList($data['ChildShards']);
    }

    /**
     * @return ChildShard[]
     */
    private function populateResultChildShardList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new ChildShard([
                'ShardId' => (string) $item['ShardId'],
                'ParentShards' => $this->populateResultShardIdList($item['ParentShards']),
                'HashKeyRange' => new HashKeyRange([
                    'StartingHashKey' => (string) $item['HashKeyRange']['StartingHashKey'],
                    'EndingHashKey' => (string) $item['HashKeyRange']['EndingHashKey'],
                ]),
            ]);
        }

        return $items;
    }

    /**
     * @return Record[]
     */
    private function populateResultRecordList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Record([
                'SequenceNumber' => (string) $item['SequenceNumber'],
                'ApproximateArrivalTimestamp' => (isset($item['ApproximateArrivalTimestamp']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $item['ApproximateArrivalTimestamp'])))) ? $d : null,
                'Data' => base64_decode((string) $item['Data']),
                'PartitionKey' => (string) $item['PartitionKey'],
                'EncryptionType' => isset($item['EncryptionType']) ? (string) $item['EncryptionType'] : null,
            ]);
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultShardIdList(array $json): array
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
}
