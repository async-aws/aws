<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Information about item collections, if any, that were affected by the `DeleteItem` operation. `ItemCollectionMetrics`
 * is only returned if the `ReturnItemCollectionMetrics` parameter was specified. If the table does not have any local
 * secondary indexes, this information is not returned in the response.
 * Each `ItemCollectionMetrics` element consists of:.
 *
 * - `ItemCollectionKey` - The partition key value of the item collection. This is the same as the partition key value
 *   of the item itself.
 * - `SizeEstimateRangeGB` - An estimate of item collection size, in gigabytes. This value is a two-element array
 *   containing a lower bound and an upper bound for the estimate. The estimate includes the size of all the items in
 *   the table, plus the size of all attributes projected into all of the local secondary indexes on that table. Use
 *   this estimate to measure whether a local secondary index is approaching its size limit.
 *   The estimate is subject to change over time; therefore, do not rely on the precision or accuracy of the estimate.
 */
final class ItemCollectionMetrics
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
     *   ItemCollectionKey?: null|array<string, AttributeValue>,
     *   SizeEstimateRangeGB?: null|float[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ItemCollectionKey = isset($input['ItemCollectionKey']) ? array_map([AttributeValue::class, 'create'], $input['ItemCollectionKey']) : null;
        $this->SizeEstimateRangeGB = $input['SizeEstimateRangeGB'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getItemCollectionKey(): array
    {
        return $this->ItemCollectionKey ?? [];
    }

    /**
     * @return float[]
     */
    public function getSizeEstimateRangeGB(): array
    {
        return $this->SizeEstimateRangeGB ?? [];
    }
}
