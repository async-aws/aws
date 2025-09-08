<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the properties of a replica global secondary index.
 */
final class ReplicaGlobalSecondaryIndexDescription
{
    /**
     * The name of the global secondary index.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * If not described, uses the source table GSI's read capacity settings.
     *
     * @var ProvisionedThroughputOverride|null
     */
    private $provisionedThroughputOverride;

    /**
     * Overrides the maximum on-demand throughput for the specified global secondary index in the specified replica table.
     *
     * @var OnDemandThroughputOverride|null
     */
    private $onDemandThroughputOverride;

    /**
     * Represents the warm throughput of the global secondary index for this replica.
     *
     * @var GlobalSecondaryIndexWarmThroughputDescription|null
     */
    private $warmThroughput;

    /**
     * @param array{
     *   IndexName?: string|null,
     *   ProvisionedThroughputOverride?: ProvisionedThroughputOverride|array|null,
     *   OnDemandThroughputOverride?: OnDemandThroughputOverride|array|null,
     *   WarmThroughput?: GlobalSecondaryIndexWarmThroughputDescription|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->provisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
        $this->onDemandThroughputOverride = isset($input['OnDemandThroughputOverride']) ? OnDemandThroughputOverride::create($input['OnDemandThroughputOverride']) : null;
        $this->warmThroughput = isset($input['WarmThroughput']) ? GlobalSecondaryIndexWarmThroughputDescription::create($input['WarmThroughput']) : null;
    }

    /**
     * @param array{
     *   IndexName?: string|null,
     *   ProvisionedThroughputOverride?: ProvisionedThroughputOverride|array|null,
     *   OnDemandThroughputOverride?: OnDemandThroughputOverride|array|null,
     *   WarmThroughput?: GlobalSecondaryIndexWarmThroughputDescription|array|null,
     * }|ReplicaGlobalSecondaryIndexDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getOnDemandThroughputOverride(): ?OnDemandThroughputOverride
    {
        return $this->onDemandThroughputOverride;
    }

    public function getProvisionedThroughputOverride(): ?ProvisionedThroughputOverride
    {
        return $this->provisionedThroughputOverride;
    }

    public function getWarmThroughput(): ?GlobalSecondaryIndexWarmThroughputDescription
    {
        return $this->warmThroughput;
    }
}
