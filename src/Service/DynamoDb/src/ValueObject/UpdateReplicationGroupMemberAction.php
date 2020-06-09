<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class UpdateReplicationGroupMemberAction
{
    /**
     * The Region where the replica exists.
     */
    private $RegionName;

    /**
     * The AWS KMS customer master key (CMK) of the replica that should be used for AWS KMS encryption. To specify a CMK,
     * use its key ID, Amazon Resource Name (ARN), alias name, or alias ARN. Note that you should only provide this
     * parameter if the key is different from the default DynamoDB KMS master key alias/aws/dynamodb.
     */
    private $KMSMasterKeyId;

    /**
     * Replica-specific provisioned throughput. If not specified, uses the source table's provisioned throughput settings.
     */
    private $ProvisionedThroughputOverride;

    /**
     * Replica-specific global secondary index settings.
     */
    private $GlobalSecondaryIndexes;

    /**
     * @param array{
     *   RegionName: string,
     *   KMSMasterKeyId?: null|string,
     *   ProvisionedThroughputOverride?: null|\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputOverride|array,
     *   GlobalSecondaryIndexes?: null|\AsyncAws\DynamoDb\ValueObject\ReplicaGlobalSecondaryIndex[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->RegionName = $input['RegionName'] ?? null;
        $this->KMSMasterKeyId = $input['KMSMasterKeyId'] ?? null;
        $this->ProvisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
        $this->GlobalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([ReplicaGlobalSecondaryIndex::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
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

    public function getRegionName(): string
    {
        return $this->RegionName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->RegionName) {
            throw new InvalidArgument(sprintf('Missing parameter "RegionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RegionName'] = $v;
        if (null !== $v = $this->KMSMasterKeyId) {
            $payload['KMSMasterKeyId'] = $v;
        }
        if (null !== $v = $this->ProvisionedThroughputOverride) {
            $payload['ProvisionedThroughputOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->GlobalSecondaryIndexes) {
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
