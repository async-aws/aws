<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Status of the cache parameter group.
 */
final class CacheParameterGroupStatus
{
    /**
     * The name of the cache parameter group.
     *
     * @var string|null
     */
    private $cacheParameterGroupName;

    /**
     * The status of parameter updates.
     *
     * @var string|null
     */
    private $parameterApplyStatus;

    /**
     * A list of the cache node IDs which need to be rebooted for parameter changes to be applied. A node ID is a numeric
     * identifier (0001, 0002, etc.).
     *
     * @var string[]|null
     */
    private $cacheNodeIdsToReboot;

    /**
     * @param array{
     *   CacheParameterGroupName?: string|null,
     *   ParameterApplyStatus?: string|null,
     *   CacheNodeIdsToReboot?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cacheParameterGroupName = $input['CacheParameterGroupName'] ?? null;
        $this->parameterApplyStatus = $input['ParameterApplyStatus'] ?? null;
        $this->cacheNodeIdsToReboot = $input['CacheNodeIdsToReboot'] ?? null;
    }

    /**
     * @param array{
     *   CacheParameterGroupName?: string|null,
     *   ParameterApplyStatus?: string|null,
     *   CacheNodeIdsToReboot?: string[]|null,
     * }|CacheParameterGroupStatus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getCacheNodeIdsToReboot(): array
    {
        return $this->cacheNodeIdsToReboot ?? [];
    }

    public function getCacheParameterGroupName(): ?string
    {
        return $this->cacheParameterGroupName;
    }

    public function getParameterApplyStatus(): ?string
    {
        return $this->parameterApplyStatus;
    }
}
