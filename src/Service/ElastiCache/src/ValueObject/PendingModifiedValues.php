<?php

namespace AsyncAws\ElastiCache\ValueObject;

use AsyncAws\ElastiCache\Enum\AuthTokenUpdateStatus;
use AsyncAws\ElastiCache\Enum\TransitEncryptionMode;

/**
 * A group of settings that are applied to the cluster in the future, or that are currently being applied.
 */
final class PendingModifiedValues
{
    /**
     * The new number of cache nodes for the cluster.
     *
     * For clusters running Redis OSS, this value must be 1. For clusters running Memcached, this value must be between 1
     * and 40.
     *
     * @var int|null
     */
    private $numCacheNodes;

    /**
     * A list of cache node IDs that are being removed (or will be removed) from the cluster. A node ID is a 4-digit numeric
     * identifier (0001, 0002, etc.).
     *
     * @var string[]|null
     */
    private $cacheNodeIdsToRemove;

    /**
     * The new cache engine version that the cluster runs.
     *
     * @var string|null
     */
    private $engineVersion;

    /**
     * The cache node type that this cluster or replication group is scaled to.
     *
     * @var string|null
     */
    private $cacheNodeType;

    /**
     * The auth token status.
     *
     * @var AuthTokenUpdateStatus::*|null
     */
    private $authTokenStatus;

    /**
     * The log delivery configurations being modified.
     *
     * @var PendingLogDeliveryConfiguration[]|null
     */
    private $logDeliveryConfigurations;

    /**
     * A flag that enables in-transit encryption when set to true.
     *
     * @var bool|null
     */
    private $transitEncryptionEnabled;

    /**
     * A setting that allows you to migrate your clients to use in-transit encryption, with no downtime.
     *
     * @var TransitEncryptionMode::*|null
     */
    private $transitEncryptionMode;

    /**
     * @param array{
     *   NumCacheNodes?: null|int,
     *   CacheNodeIdsToRemove?: null|string[],
     *   EngineVersion?: null|string,
     *   CacheNodeType?: null|string,
     *   AuthTokenStatus?: null|AuthTokenUpdateStatus::*,
     *   LogDeliveryConfigurations?: null|array<PendingLogDeliveryConfiguration|array>,
     *   TransitEncryptionEnabled?: null|bool,
     *   TransitEncryptionMode?: null|TransitEncryptionMode::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->numCacheNodes = $input['NumCacheNodes'] ?? null;
        $this->cacheNodeIdsToRemove = $input['CacheNodeIdsToRemove'] ?? null;
        $this->engineVersion = $input['EngineVersion'] ?? null;
        $this->cacheNodeType = $input['CacheNodeType'] ?? null;
        $this->authTokenStatus = $input['AuthTokenStatus'] ?? null;
        $this->logDeliveryConfigurations = isset($input['LogDeliveryConfigurations']) ? array_map([PendingLogDeliveryConfiguration::class, 'create'], $input['LogDeliveryConfigurations']) : null;
        $this->transitEncryptionEnabled = $input['TransitEncryptionEnabled'] ?? null;
        $this->transitEncryptionMode = $input['TransitEncryptionMode'] ?? null;
    }

    /**
     * @param array{
     *   NumCacheNodes?: null|int,
     *   CacheNodeIdsToRemove?: null|string[],
     *   EngineVersion?: null|string,
     *   CacheNodeType?: null|string,
     *   AuthTokenStatus?: null|AuthTokenUpdateStatus::*,
     *   LogDeliveryConfigurations?: null|array<PendingLogDeliveryConfiguration|array>,
     *   TransitEncryptionEnabled?: null|bool,
     *   TransitEncryptionMode?: null|TransitEncryptionMode::*,
     * }|PendingModifiedValues $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AuthTokenUpdateStatus::*|null
     */
    public function getAuthTokenStatus(): ?string
    {
        return $this->authTokenStatus;
    }

    /**
     * @return string[]
     */
    public function getCacheNodeIdsToRemove(): array
    {
        return $this->cacheNodeIdsToRemove ?? [];
    }

    public function getCacheNodeType(): ?string
    {
        return $this->cacheNodeType;
    }

    public function getEngineVersion(): ?string
    {
        return $this->engineVersion;
    }

    /**
     * @return PendingLogDeliveryConfiguration[]
     */
    public function getLogDeliveryConfigurations(): array
    {
        return $this->logDeliveryConfigurations ?? [];
    }

    public function getNumCacheNodes(): ?int
    {
        return $this->numCacheNodes;
    }

    public function getTransitEncryptionEnabled(): ?bool
    {
        return $this->transitEncryptionEnabled;
    }

    /**
     * @return TransitEncryptionMode::*|null
     */
    public function getTransitEncryptionMode(): ?string
    {
        return $this->transitEncryptionMode;
    }
}
