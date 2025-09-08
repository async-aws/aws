<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\TableClass;

/**
 * Represents a replica to be modified.
 */
final class UpdateReplicationGroupMemberAction
{
    /**
     * The Region where the replica exists.
     *
     * @var string
     */
    private $regionName;

    /**
     * The KMS key of the replica that should be used for KMS encryption. To specify a key, use its key ID, Amazon Resource
     * Name (ARN), alias name, or alias ARN. Note that you should only provide this parameter if the key is different from
     * the default DynamoDB KMS key `alias/aws/dynamodb`.
     *
     * @var string|null
     */
    private $kmsMasterKeyId;

    /**
     * Replica-specific provisioned throughput. If not specified, uses the source table's provisioned throughput settings.
     *
     * @var ProvisionedThroughputOverride|null
     */
    private $provisionedThroughputOverride;

    /**
     * Overrides the maximum on-demand throughput for the replica table.
     *
     * @var OnDemandThroughputOverride|null
     */
    private $onDemandThroughputOverride;

    /**
     * Replica-specific global secondary index settings.
     *
     * @var ReplicaGlobalSecondaryIndex[]|null
     */
    private $globalSecondaryIndexes;

    /**
     * Replica-specific table class. If not specified, uses the source table's table class.
     *
     * @var TableClass::*|null
     */
    private $tableClassOverride;

    /**
     * @param array{
     *   RegionName: string,
     *   KMSMasterKeyId?: string|null,
     *   ProvisionedThroughputOverride?: ProvisionedThroughputOverride|array|null,
     *   OnDemandThroughputOverride?: OnDemandThroughputOverride|array|null,
     *   GlobalSecondaryIndexes?: array<ReplicaGlobalSecondaryIndex|array>|null,
     *   TableClassOverride?: TableClass::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->regionName = $input['RegionName'] ?? $this->throwException(new InvalidArgument('Missing required field "RegionName".'));
        $this->kmsMasterKeyId = $input['KMSMasterKeyId'] ?? null;
        $this->provisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
        $this->onDemandThroughputOverride = isset($input['OnDemandThroughputOverride']) ? OnDemandThroughputOverride::create($input['OnDemandThroughputOverride']) : null;
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([ReplicaGlobalSecondaryIndex::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
        $this->tableClassOverride = $input['TableClassOverride'] ?? null;
    }

    /**
     * @param array{
     *   RegionName: string,
     *   KMSMasterKeyId?: string|null,
     *   ProvisionedThroughputOverride?: ProvisionedThroughputOverride|array|null,
     *   OnDemandThroughputOverride?: OnDemandThroughputOverride|array|null,
     *   GlobalSecondaryIndexes?: array<ReplicaGlobalSecondaryIndex|array>|null,
     *   TableClassOverride?: TableClass::*|null,
     * }|UpdateReplicationGroupMemberAction $input
     */
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

    public function getOnDemandThroughputOverride(): ?OnDemandThroughputOverride
    {
        return $this->onDemandThroughputOverride;
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
        $v = $this->regionName;
        $payload['RegionName'] = $v;
        if (null !== $v = $this->kmsMasterKeyId) {
            $payload['KMSMasterKeyId'] = $v;
        }
        if (null !== $v = $this->provisionedThroughputOverride) {
            $payload['ProvisionedThroughputOverride'] = $v->requestBody();
        }
        if (null !== $v = $this->onDemandThroughputOverride) {
            $payload['OnDemandThroughputOverride'] = $v->requestBody();
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
                throw new InvalidArgument(\sprintf('Invalid parameter "TableClassOverride" for "%s". The value "%s" is not a valid "TableClass".', __CLASS__, $v));
            }
            $payload['TableClassOverride'] = $v;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
