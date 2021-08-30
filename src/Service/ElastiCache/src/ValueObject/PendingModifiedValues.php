<?php

namespace AsyncAws\ElastiCache\ValueObject;

use AsyncAws\ElastiCache\Enum\AuthTokenUpdateStatus;

final class PendingModifiedValues
{
    /**
     * The new number of cache nodes for the cluster.
     */
    private $numCacheNodes;

    /**
     * A list of cache node IDs that are being removed (or will be removed) from the cluster. A node ID is a 4-digit numeric
     * identifier (0001, 0002, etc.).
     */
    private $cacheNodeIdsToRemove;

    /**
     * The new cache engine version that the cluster runs.
     */
    private $engineVersion;

    /**
     * The cache node type that this cluster or replication group is scaled to.
     */
    private $cacheNodeType;

    /**
     * The auth token status.
     */
    private $authTokenStatus;

    /**
     * The log delivery configurations being modified.
     */
    private $logDeliveryConfigurations;

    /**
     * @param array{
     *   NumCacheNodes?: null|int,
     *   CacheNodeIdsToRemove?: null|string[],
     *   EngineVersion?: null|string,
     *   CacheNodeType?: null|string,
     *   AuthTokenStatus?: null|AuthTokenUpdateStatus::*,
     *   LogDeliveryConfigurations?: null|PendingLogDeliveryConfiguration[],
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
    }

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
}
