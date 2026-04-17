<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Configuration settings for creating and managing pre-provisioned snapshots for a fast-launch enabled Windows AMI.
 */
final class FastLaunchSnapshotConfiguration
{
    /**
     * The number of pre-provisioned snapshots to keep on hand for a fast-launch enabled Windows AMI.
     *
     * @var int|null
     */
    private $targetResourceCount;

    /**
     * @param array{
     *   targetResourceCount?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->targetResourceCount = $input['targetResourceCount'] ?? null;
    }

    /**
     * @param array{
     *   targetResourceCount?: int|null,
     * }|FastLaunchSnapshotConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTargetResourceCount(): ?int
    {
        return $this->targetResourceCount;
    }
}
