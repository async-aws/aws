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
