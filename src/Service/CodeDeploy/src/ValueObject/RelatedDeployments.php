<?php

namespace AsyncAws\CodeDeploy\ValueObject;

final class RelatedDeployments
{
    /**
     * The deployment ID of the root deployment that triggered this deployment.
     */
    private $autoUpdateOutdatedInstancesRootDeploymentId;

    /**
     * The deployment IDs of 'auto-update outdated instances' deployments triggered by this deployment.
     */
    private $autoUpdateOutdatedInstancesDeploymentIds;

    /**
     * @param array{
     *   autoUpdateOutdatedInstancesRootDeploymentId?: null|string,
     *   autoUpdateOutdatedInstancesDeploymentIds?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->autoUpdateOutdatedInstancesRootDeploymentId = $input['autoUpdateOutdatedInstancesRootDeploymentId'] ?? null;
        $this->autoUpdateOutdatedInstancesDeploymentIds = $input['autoUpdateOutdatedInstancesDeploymentIds'] ?? null;
    }

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
