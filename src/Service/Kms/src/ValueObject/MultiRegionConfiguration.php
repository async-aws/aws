<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Kms\Enum\MultiRegionKeyType;

/**
 * Lists the primary and replica keys in same multi-Region key. This field is present only when the value of the
 * `MultiRegion` field is `True`.
 * For more information about any listed KMS key, use the DescribeKey operation.
 *
 * - `MultiRegionKeyType` indicates whether the KMS key is a `PRIMARY` or `REPLICA` key.
 * - `PrimaryKey` displays the key ARN and Region of the primary key. This field displays the current KMS key if it is
 *   the primary key.
 * - `ReplicaKeys` displays the key ARNs and Regions of all replica keys. This field includes the current KMS key if it
 *   is a replica key.
 */
final class MultiRegionConfiguration
{
    /**
     * Indicates whether the KMS key is a `PRIMARY` or `REPLICA` key.
     */
    private $multiRegionKeyType;

    /**
     * Displays the key ARN and Region of the primary key. This field includes the current KMS key if it is the primary key.
     */
    private $primaryKey;

    /**
     * displays the key ARNs and Regions of all replica keys. This field includes the current KMS key if it is a replica
     * key.
     */
    private $replicaKeys;

    /**
     * @param array{
     *   MultiRegionKeyType?: null|MultiRegionKeyType::*,
     *   PrimaryKey?: null|MultiRegionKey|array,
     *   ReplicaKeys?: null|MultiRegionKey[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->multiRegionKeyType = $input['MultiRegionKeyType'] ?? null;
        $this->primaryKey = isset($input['PrimaryKey']) ? MultiRegionKey::create($input['PrimaryKey']) : null;
        $this->replicaKeys = isset($input['ReplicaKeys']) ? array_map([MultiRegionKey::class, 'create'], $input['ReplicaKeys']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MultiRegionKeyType::*|null
     */
    public function getMultiRegionKeyType(): ?string
    {
        return $this->multiRegionKeyType;
    }

    public function getPrimaryKey(): ?MultiRegionKey
    {
        return $this->primaryKey;
    }

    /**
     * @return MultiRegionKey[]
     */
    public function getReplicaKeys(): array
    {
        return $this->replicaKeys ?? [];
    }
}
