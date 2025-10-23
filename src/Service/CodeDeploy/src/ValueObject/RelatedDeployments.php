<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about deployments related to the specified deployment.
 */
final class RelatedDeployments
{
    /**
     * The deployment ID of the root deployment that triggered this deployment.
     *
     * @var string|null
     */
    private $autoUpdateOutdatedInstancesRootDeploymentId;

    /**
     * The deployment IDs of 'auto-update outdated instances' deployments triggered by this deployment.
     *
     * @var string[]|null
     */
    private $autoUpdateOutdatedInstancesDeploymentIds;

    /**
     * @param array{
     *   autoUpdateOutdatedInstancesRootDeploymentId?: string|null,
     *   autoUpdateOutdatedInstancesDeploymentIds?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->autoUpdateOutdatedInstancesRootDeploymentId = $input['autoUpdateOutdatedInstancesRootDeploymentId'] ?? null;
        $this->autoUpdateOutdatedInstancesDeploymentIds = $input['autoUpdateOutdatedInstancesDeploymentIds'] ?? null;
    }

    /**
     * @param array{
     *   autoUpdateOutdatedInstancesRootDeploymentId?: string|null,
     *   autoUpdateOutdatedInstancesDeploymentIds?: string[]|null,
     * }|RelatedDeployments $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAutoUpdateOutdatedInstancesDeploymentIds(): array
    {
        return $this->autoUpdateOutdatedInstancesDeploymentIds ?? [];
    }

    public function getAutoUpdateOutdatedInstancesRootDeploymentId(): ?string
    {
        return $this->autoUpdateOutdatedInstancesRootDeploymentId;
    }
}
