<?php

namespace AsyncAws\DynamoDb\ValueObject;

class ItemCollectionMetrics
{
    /**
     * The partition key value of the item collection. This value is the same as the partition key value of the item.
     */
    private $ItemCollectionKey;

    /**
     * An estimate of item collection size, in gigabytes. This value is a two-element array containing a lower bound and an
     * upper bound for the estimate. The estimate includes the size of all the items in the table, plus the size of all
     * attributes projected into all of the local secondary indexes on that table. Use this estimate to measure whether a
     * local secondary index is approaching its size limit.
     */
    private $SizeEstimateRangeGB;

    /**
     * @param array{
     *   ItemCollectionKey?: null|\AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     *   SizeEstimateRangeGB?: null|float[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ItemCollectionKey = array_map([AttributeValue::class, 'create'], $input['ItemCollectionKey'] ?? []);
        $this->SizeEstimateRangeGB = $input['SizeEstimateRangeGB'] ?? [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AttributeValue[]
     */
    public function getItemCollectionKey(): array
    {
        return $this->ItemCollectionKey;
    }

    /**
     * @return float[]
     */
    public function getSizeEstimateRangeGB(): array
    {
        return $this->SizeEstimateRangeGB;
    }
}
