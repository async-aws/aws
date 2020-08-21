<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\ReplicaStatus;

final class ReplicaDescription
{
    /**
     * The name of the Region.
     */
    private $RegionName;

    /**
     * The current state of the replica:.
     */
    private $ReplicaStatus;

    /**
     * Detailed information about the replica status.
     */
    private $ReplicaStatusDescription;

    /**
     * Specifies the progress of a Create, Update, or Delete action on the replica as a percentage.
     */
    private $ReplicaStatusPercentProgress;

    /**
     * The AWS KMS customer master key (CMK) of the replica that will be used for AWS KMS encryption.
     */
    private $KMSMasterKeyId;

    /**
     * Replica-specific provisioned throughput. If not described, uses the source table's provisioned throughput settings.
     */
    private $ProvisionedThroughputOverride;

    /**
     * Replica-specific global secondary index settings.
     */
    private $GlobalSecondaryIndexes;

    /**
     * @param array{
     *   RegionName?: null|string,
     *   ReplicaStatus?: null|ReplicaStatus::*,
     *   ReplicaStatusDescription?: null|string,
     *   ReplicaStatusPercentProgress?: null|string,
     *   KMSMasterKeyId?: null|string,
     *   ProvisionedThroughputOverride?: null|ProvisionedThroughputOverride|array,
     *   GlobalSecondaryIndexes?: null|ReplicaGlobalSecondaryIndexDescription[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->RegionName = $input['RegionName'] ?? null;
        $this->ReplicaStatus = $input['ReplicaStatus'] ?? null;
        $this->ReplicaStatusDescription = $input['ReplicaStatusDescription'] ?? null;
        $this->ReplicaStatusPercentProgress = $input['ReplicaStatusPercentProgress'] ?? null;
        $this->KMSMasterKeyId = $input['KMSMasterKeyId'] ?? null;
        $this->ProvisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
        $this->GlobalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([ReplicaGlobalSecondaryIndexDescription::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
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
        return $this->GlobalSecondaryIndexes ?? [];
    }

    public function getKMSMasterKeyId(): ?string
    {
        return $this->KMSMasterKeyId;
    }

    public function getProvisionedThroughputOverride(): ?ProvisionedThroughputOverride
    {
        return $this->ProvisionedThroughputOverride;
    }

    public function getRegionName(): ?string
    {
        return $this->RegionName;
    }

    /**
     * @return ReplicaStatus::*|null
     */
    public function getReplicaStatus(): ?string
    {
        return $this->ReplicaStatus;
    }

    public function getReplicaStatusDescription(): ?string
    {
        return $this->ReplicaStatusDescription;
    }

    public function getReplicaStatusPercentProgress(): ?string
    {
        return $this->ReplicaStatusPercentProgress;
    }
}
