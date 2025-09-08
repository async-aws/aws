<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Information about item collections, if any, that were affected by the operation. `ItemCollectionMetrics` is only
 * returned if the request asked for it. If the table does not have any local secondary indexes, this information is not
 * returned in the response.
 */
final class ItemCollectionMetrics
{
    /**
     * The partition key value of the item collection. This value is the same as the partition key value of the item.
     *
     * @var array<string, AttributeValue>|null
     */
    private $itemCollectionKey;

    /**
     * An estimate of item collection size, in gigabytes. This value is a two-element array containing a lower bound and an
     * upper bound for the estimate. The estimate includes the size of all the items in the table, plus the size of all
     * attributes projected into all of the local secondary indexes on that table. Use this estimate to measure whether a
     * local secondary index is approaching its size limit.
     *
     * The estimate is subject to change over time; therefore, do not rely on the precision or accuracy of the estimate.
     *
     * @var float[]|null
     */
    private $sizeEstimateRangeGb;

    /**
     * @param array{
     *   ItemCollectionKey?: array<string, AttributeValue|array>|null,
     *   SizeEstimateRangeGB?: float[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->itemCollectionKey = isset($input['ItemCollectionKey']) ? array_map([AttributeValue::class, 'create'], $input['ItemCollectionKey']) : null;
        $this->sizeEstimateRangeGb = $input['SizeEstimateRangeGB'] ?? null;
    }

    /**
     * @param array{
     *   ItemCollectionKey?: array<string, AttributeValue|array>|null,
     *   SizeEstimateRangeGB?: float[]|null,
     * }|ItemCollectionMetrics $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getItemCollectionKey(): array
    {
        return $this->itemCollectionKey ?? [];
    }

    /**
     * @return float[]
     */
    public function getSizeEstimateRangeGb(): array
    {
        return $this->sizeEstimateRangeGb ?? [];
    }
}
