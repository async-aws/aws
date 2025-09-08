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
     *
     * @var DeploymentType::*|null
     */
    private $deploymentType;

    /**
     * Indicates whether to route deployment traffic behind a load balancer.
     *
     * @var DeploymentOption::*|null
     */
    private $deploymentOption;

    /**
     * @param array{
     *   deploymentType?: DeploymentType::*|null,
     *   deploymentOption?: DeploymentOption::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deploymentType = $input['deploymentType'] ?? null;
        $this->deploymentOption = $input['deploymentOption'] ?? null;
    }

    /**
     * @param array{
     *   deploymentType?: DeploymentType::*|null,
     *   deploymentOption?: DeploymentOption::*|null,
     * }|DeploymentStyle $input
     */
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
