<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\TableClass;

/**
 * The parameters required for creating a replica for the table.
 */
final class CreateReplicationGroupMemberAction
{
    /**
     * The Region where the new replica will be created.
     */
    private $regionName;

    /**
     * The KMS key that should be used for KMS encryption in the new replica. To specify a key, use its key ID, Amazon
     * Resource Name (ARN), alias name, or alias ARN. Note that you should only provide this parameter if the key is
     * different from the default DynamoDB KMS key `alias/aws/dynamodb`.
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
     * Replica-specific table class. If not specified, uses the source table's table class.
     */
    private $tableClassOverride;

    /**
     * @param array{
     *   RegionName: string,
     *   KMSMasterKeyId?: null|string,
     *   ProvisionedThroughputOverride?: null|ProvisionedThroughputOverride|array,
     *   GlobalSecondaryIndexes?: null|ReplicaGlobalSecondaryIndex[],
     *   TableClassOverride?: null|TableClass::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->regionName = $input['RegionName'] ?? null;
        $this->kmsMasterKeyId = $input['KMSMasterKeyId'] ?? null;
        $this->provisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([ReplicaGlobalSecondaryIndex::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
        $this->tableClassOverride = $input['TableClassOverride'] ?? null;
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
     * @return TableClass::*|null
     */
    public function getTableClassOverride(): ?string
    {
        return $this->tableClassOverride;
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
        if (null !== $v = $this->tableClassOverride) {
            if (!TableClass::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "TableClassOverride" for "%s". The value "%s" is not a valid "TableClass".', __CLASS__, $v));
            }
            $payload['TableClassOverride'] = $v;
        }

        return $payload;
    }
}
