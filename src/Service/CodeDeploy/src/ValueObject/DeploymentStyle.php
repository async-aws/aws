<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\DeploymentOption;
use AsyncAws\CodeDeploy\Enum\DeploymentType;

/**
 * Information about the type of deployment, either in-place or blue/green, you want to run and whether to route
 * deployment traffic behind a load balancer.
 */
final class DeploymentStyle
{
    /**
     * Indicates whether to run an in-place deployment or a blue/green deployment.
     */
    private $deploymentType;

    /**
     * Indicates whether to route deployment traffic behind a load balancer.
     */
    private $deploymentOption;

    /**
     * @param array{
     *   deploymentType?: null|DeploymentType::*,
     *   deploymentOption?: null|DeploymentOption::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deploymentType = $input['deploymentType'] ?? null;
        $this->deploymentOption = $input['deploymentOption'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DeploymentOption::*|null
     */
    public function getDeploymentOption(): ?string
    {
        return $this->deploymentOption;
    }

    /**
     * @return DeploymentType::*|null
     */
    public function getDeploymentType(): ?string
    {
        return $this->deploymentType;
    }
}
