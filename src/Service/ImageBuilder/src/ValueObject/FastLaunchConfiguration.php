<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Defines and configures EC2 Fast Launch for output Windows AMIs.
 */
final class FastLaunchConfiguration
{
    /**
     * Specifies whether to enable Windows fast launch on the output AMI during distribution. A value of `false` means Image
     * Builder takes no fast-launch action for this configuration.
     *
     * @var bool
     */
    private $enabled;

    /**
     * Configuration settings for managing the number of snapshots that are created from pre-provisioned instances for the
     * Windows AMI when Windows fast launch is enabled.
     *
     * @var FastLaunchSnapshotConfiguration|null
     */
    private $snapshotConfiguration;

    /**
     * The maximum number of parallel instances that are launched for creating resources.
     *
     * @var int|null
     */
    private $maxParallelLaunches;

    /**
     * The launch template that the fast-launch enabled Windows AMI uses when it launches Windows instances to create
     * pre-provisioned snapshots.
     *
     * @var FastLaunchLaunchTemplateSpecification|null
     */
    private $launchTemplate;

    /**
     * The owner account ID for the fast-launch enabled Windows AMI.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * @param array{
     *   enabled: bool,
     *   snapshotConfiguration?: FastLaunchSnapshotConfiguration|array|null,
     *   maxParallelLaunches?: int|null,
     *   launchTemplate?: FastLaunchLaunchTemplateSpecification|array|null,
     *   accountId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "enabled".'));
        $this->snapshotConfiguration = isset($input['snapshotConfiguration']) ? FastLaunchSnapshotConfiguration::create($input['snapshotConfiguration']) : null;
        $this->maxParallelLaunches = $input['maxParallelLaunches'] ?? null;
        $this->launchTemplate = isset($input['launchTemplate']) ? FastLaunchLaunchTemplateSpecification::create($input['launchTemplate']) : null;
        $this->accountId = $input['accountId'] ?? null;
    }

    /**
     * @param array{
     *   enabled: bool,
     *   snapshotConfiguration?: FastLaunchSnapshotConfiguration|array|null,
     *   maxParallelLaunches?: int|null,
     *   launchTemplate?: FastLaunchLaunchTemplateSpecification|array|null,
     *   accountId?: string|null,
     * }|FastLaunchConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getLaunchTemplate(): ?FastLaunchLaunchTemplateSpecification
    {
        return $this->launchTemplate;
    }

    public function getMaxParallelLaunches(): ?int
    {
        return $this->maxParallelLaunches;
    }

    public function getSnapshotConfiguration(): ?FastLaunchSnapshotConfiguration
    {
        return $this->snapshotConfiguration;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
