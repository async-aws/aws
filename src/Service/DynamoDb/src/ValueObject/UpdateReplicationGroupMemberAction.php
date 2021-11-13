<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The parameters required for updating a replica for the table.
 */
final class UpdateReplicationGroupMemberAction
{
    /**
     * The Region where the replica exists.
     */
    private $regionName;

    /**
     * The KMS key of the replica that should be used for KMS encryption. To specify a key, use its key ID, Amazon Resource
     * Name (ARN), alias name, or alias ARN. Note that you should only provide this parameter if the key is different from
     * the default DynamoDB KMS key `alias/aws/dynamodb`.
     */
    private $kmsMasterKeyId;

    /**
     * Replica-specific provisioned throughput. If not specified, uses the source table's provisioned throughput settings.
     */
    private $provisionedThroughputOverride;

    /**
     * Replica-specific global secondary index settings.
     */
    private $globalSecondaryIndexes;

    /**
     * @param array{
     *   RegionName: string,
     *   KMSMasterKeyId?: null|string,
     *   ProvisionedThroughputOverride?: null|ProvisionedThroughputOverride|array,
     *   GlobalSecondaryIndexes?: null|ReplicaGlobalSecondaryIndex[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->regionName = $input['RegionName'] ?? null;
        $this->kmsMasterKeyId = $input['KMSMasterKeyId'] ?? null;
        $this->provisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([ReplicaGlobalSecondaryIndex::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ReplicaGlobalSecondaryIndex[]
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

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->regionName) {
            throw new InvalidArgument(sprintf('Missing parameter "RegionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RegionName'] = $v;
        if (null !== $v = $this->kmsMasterKeyId) {
            $payload['KMSMasterKeyId'] = $v;
        }
        if (null !== $v = $this->provisionedThroughputOverride) {
            $payload['ProvisionedThroughputOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->globalSecondaryIndexes) {
            $index = -1;
            $payload['GlobalSecondaryIndexes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['GlobalSecondaryIndexes'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
