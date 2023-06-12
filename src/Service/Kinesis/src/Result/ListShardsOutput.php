<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\ValueObject\HashKeyRange;
use AsyncAws\Kinesis\ValueObject\SequenceNumberRange;
use AsyncAws\Kinesis\ValueObject\Shard;

class ListShardsOutput extends Result
{
    /**
     * An array of JSON objects. Each object represents one shard and specifies the IDs of the shard, the shard's parent,
     * and the shard that's adjacent to the shard's parent. Each object also contains the starting and ending hash keys and
     * the starting and ending sequence numbers for the shard.
     */
    private $shards;

    /**
     * When the number of shards in the data stream is greater than the default value for the `MaxResults` parameter, or if
     * you explicitly specify a value for `MaxResults` that is less than the number of shards in the data stream, the
     * response includes a pagination token named `NextToken`. You can specify this `NextToken` value in a subsequent call
     * to `ListShards` to list the next set of shards. For more information about the use of this pagination token when
     * calling the `ListShards` operation, see ListShardsInput$NextToken.
     *
     * ! Tokens expire after 300 seconds. When you obtain a value for `NextToken` in the response to a call to `ListShards`,
     * ! you have 300 seconds to use that value. If you specify an expired token in a call to `ListShards`, you get
     * ! `ExpiredNextTokenException`.
     */
    private $nextToken;

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @return Shard[]
     */
    public function getShards(): array
    {
        $this->initialize();

        return $this->shards;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->shards = empty($data['Shards']) ? [] : $this->populateResultShardList($data['Shards']);
        $this->nextToken = isset($data['NextToken']) ? (string) $data['NextToken'] : null;
    }

    private function populateResultHashKeyRange(array $json): HashKeyRange
    {
        return new HashKeyRange([
            'StartingHashKey' => (string) $json['StartingHashKey'],
            'EndingHashKey' => (string) $json['EndingHashKey'],
        ]);
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
}
