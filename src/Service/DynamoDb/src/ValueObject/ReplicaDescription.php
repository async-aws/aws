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
     */
    private $regionName;

    /**
     * The current state of the replica:.
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
     */
    private $replicaStatus;

    /**
     * Detailed information about the replica status.
     */
    private $replicaStatusDescription;

    /**
     * Specifies the progress of a Create, Update, or Delete action on the replica as a percentage.
     */
    private $replicaStatusPercentProgress;

    /**
     * The KMS key of the replica that will be used for KMS encryption.
     */
    private $kmsMasterKeyId;

    /**
     * Replica-specific provisioned throughput. If not described, uses the source table's provisioned throughput settings.
     */
    private $provisionedThroughputOverride;

    /**
     * Replica-specific global secondary index settings.
     */
    private $globalSecondaryIndexes;

    /**
     * The time at which the replica was first detected as inaccessible. To determine cause of inaccessibility check the
     * `ReplicaStatus` property.
     */
    private $replicaInaccessibleDateTime;

    private $replicaTableClassSummary;

    /**
     * @param array{
     *   RegionName?: null|string,
     *   ReplicaStatus?: null|ReplicaStatus::*,
     *   ReplicaStatusDescription?: null|string,
     *   ReplicaStatusPercentProgress?: null|string,
     *   KMSMasterKeyId?: null|string,
     *   ProvisionedThroughputOverride?: null|ProvisionedThroughputOverride|array,
     *   GlobalSecondaryIndexes?: null|ReplicaGlobalSecondaryIndexDescription[],
     *   ReplicaInaccessibleDateTime?: null|\DateTimeImmutable,
     *   ReplicaTableClassSummary?: null|TableClassSummary|array,
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
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([ReplicaGlobalSecondaryIndexDescription::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
        $this->replicaInaccessibleDateTime = $input['ReplicaInaccessibleDateTime'] ?? null;
        $this->replicaTableClassSummary = isset($input['ReplicaTableClassSummary']) ? TableClassSummary::create($input['ReplicaTableClassSummary']) : null;
    }

    /**
     * @param array{
     *   RegionName?: null|string,
     *   ReplicaStatus?: null|ReplicaStatus::*,
     *   ReplicaStatusDescription?: null|string,
     *   ReplicaStatusPercentProgress?: null|string,
     *   KMSMasterKeyId?: null|string,
     *   ProvisionedThroughputOverride?: null|ProvisionedThroughputOverride|array,
     *   GlobalSecondaryIndexes?: null|ReplicaGlobalSecondaryIndexDescription[],
     *   ReplicaInaccessibleDateTime?: null|\DateTimeImmutable,
     *   ReplicaTableClassSummary?: null|TableClassSummary|array,
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
}
