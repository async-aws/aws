<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about blue/green deployment options for this deployment.
 */
final class BlueGreenDeploymentConfiguration
{
    /**
     * Information about whether to terminate instances in the original fleet during a blue/green deployment.
     */
    private $terminateBlueInstancesOnDeploymentSuccess;

    /**
     * Information about the action to take when newly provisioned instances are ready to receive traffic in a blue/green
     * deployment.
     */
    private $deploymentReadyOption;

    /**
     * Information about how instances are provisioned for a replacement environment in a blue/green deployment.
     */
    private $greenFleetProvisioningOption;

    /**
     * @param array{
     *   terminateBlueInstancesOnDeploymentSuccess?: null|BlueInstanceTerminationOption|array,
     *   deploymentReadyOption?: null|DeploymentReadyOption|array,
     *   greenFleetProvisioningOption?: null|GreenFleetProvisioningOption|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->terminateBlueInstancesOnDeploymentSuccess = isset($input['terminateBlueInstancesOnDeploymentSuccess']) ? BlueInstanceTerminationOption::create($input['terminateBlueInstancesOnDeploymentSuccess']) : null;
        $this->deploymentReadyOption = isset($input['deploymentReadyOption']) ? DeploymentReadyOption::create($input['deploymentReadyOption']) : null;
        $this->greenFleetProvisioningOption = isset($input['greenFleetProvisioningOption']) ? GreenFleetProvisioningOption::create($input['greenFleetProvisioningOption']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeploymentReadyOption(): ?DeploymentReadyOption
    {
        return $this->deploymentReadyOption;
    }

    public function getGreenFleetProvisioningOption(): ?GreenFleetProvisioningOption
    {
        return $this->greenFleetProvisioningOption;
    }

    public function getTerminateBlueInstancesOnDeploymentSuccess(): ?BlueInstanceTerminationOption
    {
        return $this->terminateBlueInstancesOnDeploymentSuccess;
    }
}
