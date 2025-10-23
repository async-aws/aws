<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\ReplicaStatus;

/**
 * Contains the details of the replica.
 */
final class ReplicaDescription
{
    /**
     * The name of the Region.
     *
     * @var string|null
     */
    private $regionName;

    /**
     * The current state of the replica:
     *
     * - `CREATING` - The replica is being created.
     * - `UPDATING` - The replica is being updated.
     * - `DELETING` - The replica is being deleted.
     * - `ACTIVE` - The replica is ready for use.
     * - `REGION_DISABLED` - The replica is inaccessible because the Amazon Web Services Region has been disabled.
     *
     *   > If the Amazon Web Services Region remains inaccessible for more than 20 hours, DynamoDB will remove this replica
     *   > from the replication group. The replica will not be deleted and replication will stop from and to this region.
     *
     * - `INACCESSIBLE_ENCRYPTION_CREDENTIALS ` - The KMS key used to encrypt the table is inaccessible.
     *
     *   > If the KMS key remains inaccessible for more than 20 hours, DynamoDB will remove this replica from the
     *   > replication group. The replica will not be deleted and replication will stop from and to this region.
     *
     * @var ReplicaStatus::*|null
     */
    private $replicaStatus;

    /**
     * Detailed information about the replica status.
     *
     * @var string|null
     */
    private $replicaStatusDescription;

    /**
     * Specifies the progress of a Create, Update, or Delete action on the replica as a percentage.
     *
     * @var string|null
     */
    private $replicaStatusPercentProgress;

    /**
     * The KMS key of the replica that will be used for KMS encryption.
     *
     * @var string|null
     */
    private $kmsMasterKeyId;

    /**
     * Replica-specific provisioned throughput. If not described, uses the source table's provisioned throughput settings.
     *
     * @var ProvisionedThroughputOverride|null
     */
    private $provisionedThroughputOverride;

    /**
     * Overrides the maximum on-demand throughput settings for the specified replica table.
     *
     * @var OnDemandThroughputOverride|null
     */
    private $onDemandThroughputOverride;

    /**
     * Represents the warm throughput value for this replica.
     *
     * @var TableWarmThroughputDescription|null
     */
    private $warmThroughput;

    /**
     * Replica-specific global secondary index settings.
     *
     * @var ReplicaGlobalSecondaryIndexDescription[]|null
     */
    private $globalSecondaryIndexes;

    /**
     * The time at which the replica was first detected as inaccessible. To determine cause of inaccessibility check the
     * `ReplicaStatus` property.
     *
     * @var \DateTimeImmutable|null
     */
    private $replicaInaccessibleDateTime;

    /**
     * @var TableClassSummary|null
     */
    private $replicaTableClassSummary;

    /**
     * @param array{
     *   RegionName?: string|null,
     *   ReplicaStatus?: ReplicaStatus::*|null,
     *   ReplicaStatusDescription?: string|null,
     *   ReplicaStatusPercentProgress?: string|null,
     *   KMSMasterKeyId?: string|null,
     *   ProvisionedThroughputOverride?: ProvisionedThroughputOverride|array|null,
     *   OnDemandThroughputOverride?: OnDemandThroughputOverride|array|null,
     *   WarmThroughput?: TableWarmThroughputDescription|array|null,
     *   GlobalSecondaryIndexes?: array<ReplicaGlobalSecondaryIndexDescription|array>|null,
     *   ReplicaInaccessibleDateTime?: \DateTimeImmutable|null,
     *   ReplicaTableClassSummary?: TableClassSummary|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->regionName = $input['RegionName'] ?? null;
        $this->replicaStatus = $input['ReplicaStatus'] ?? null;
        $this->replicaStatusDescription = $input['ReplicaStatusDescription'] ?? null;
        $this->replicaStatusPercentProgress = $input['ReplicaStatusPercentProgress'] ?? null;
        $this->kmsMasterKeyId = $input['KMSMasterKeyId'] ?? null;
        $this->provisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
        $this->onDemandThroughputOverride = isset($input['OnDemandThroughputOverride']) ? OnDemandThroughputOverride::create($input['OnDemandThroughputOverride']) : null;
        $this->warmThroughput = isset($input['WarmThroughput']) ? TableWarmThroughputDescription::create($input['WarmThroughput']) : null;
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([ReplicaGlobalSecondaryIndexDescription::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
        $this->replicaInaccessibleDateTime = $input['ReplicaInaccessibleDateTime'] ?? null;
        $this->replicaTableClassSummary = isset($input['ReplicaTableClassSummary']) ? TableClassSummary::create($input['ReplicaTableClassSummary']) : null;
    }

    /**
     * @param array{
     *   RegionName?: string|null,
     *   ReplicaStatus?: ReplicaStatus::*|null,
     *   ReplicaStatusDescription?: string|null,
     *   ReplicaStatusPercentProgress?: string|null,
     *   KMSMasterKeyId?: string|null,
     *   ProvisionedThroughputOverride?: ProvisionedThroughputOverride|array|null,
     *   OnDemandThroughputOverride?: OnDemandThroughputOverride|array|null,
     *   WarmThroughput?: TableWarmThroughputDescription|array|null,
     *   GlobalSecondaryIndexes?: array<ReplicaGlobalSecondaryIndexDescription|array>|null,
     *   ReplicaInaccessibleDateTime?: \DateTimeImmutable|null,
     *   ReplicaTableClassSummary?: TableClassSummary|array|null,
     * }|ReplicaDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ReplicaGlobalSecondaryIndexDescription[]
     */
    public function getGlobalSecondaryIndexes(): array
    {
        return $this->globalSecondaryIndexes ?? [];
    }

    public function getKmsMasterKeyId(): ?string
    {
        return $this->kmsMasterKeyId;
    }

    public function getOnDemandThroughputOverride(): ?OnDemandThroughputOverride
    {
        return $this->onDemandThroughputOverride;
    }

    public function getProvisionedThroughputOverride(): ?ProvisionedThroughputOverride
    {
        return $this->provisionedThroughputOverride;
    }

    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    public function getReplicaInaccessibleDateTime(): ?\DateTimeImmutable
    {
        return $this->replicaInaccessibleDateTime;
    }

    /**
     * @return ReplicaStatus::*|null
     */
    public function getReplicaStatus(): ?string
    {
        return $this->replicaStatus;
    }

    public function getReplicaStatusDescription(): ?string
    {
        return $this->replicaStatusDescription;
    }

    public function getReplicaStatusPercentProgress(): ?string
    {
        return $this->replicaStatusPercentProgress;
    }

    public function getReplicaTableClassSummary(): ?TableClassSummary
    {
        return $this->replicaTableClassSummary;
    }

    public function getWarmThroughput(): ?TableWarmThroughputDescription
    {
        return $this->warmThroughput;
    }
}
