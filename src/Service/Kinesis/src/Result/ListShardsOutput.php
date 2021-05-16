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
    private $shards = [];

    /**
     * When the number of shards in the data stream is greater than the default value for the `MaxResults` parameter, or if
     * you explicitly specify a value for `MaxResults` that is less than the number of shards in the data stream, the
     * response includes a pagination token named `NextToken`. You can specify this `NextToken` value in a subsequent call
     * to `ListShards` to list the next set of shards. For more information about the use of this pagination token when
     * calling the `ListShards` operation, see ListShardsInput$NextToken.
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
