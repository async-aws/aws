<?php

namespace AsyncAws\Kms\ValueObject;

use AsyncAws\Kms\Enum\MultiRegionKeyType;

/**
 * Describes the configuration of this multi-Region key. This field appears only when the KMS key is a primary or
 * replica of a multi-Region key.
 *
 * For more information about any listed KMS key, use the DescribeKey operation.
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

    /**
     * @param array{
     *   MultiRegionKeyType?: null|MultiRegionKeyType::*,
     *   PrimaryKey?: null|MultiRegionKey|array,
     *   ReplicaKeys?: null|MultiRegionKey[],
     * }|MultiRegionConfiguration $input
     */
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
