<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about blue/green deployment options for a deployment group.
 */
final class BlueGreenDeploymentConfiguration
{
    /**
     * Information about whether to terminate instances in the original fleet during a blue/green deployment.
     *
     * @var BlueInstanceTerminationOption|null
     */
    private $terminateBlueInstancesOnDeploymentSuccess;

    /**
     * Information about the action to take when newly provisioned instances are ready to receive traffic in a blue/green
     * deployment.
     *
     * @var DeploymentReadyOption|null
     */
    private $deploymentReadyOption;

    /**
     * Information about how instances are provisioned for a replacement environment in a blue/green deployment.
     *
     * @var GreenFleetProvisioningOption|null
     */
    private $greenFleetProvisioningOption;

    /**
     * @param array{
     *   terminateBlueInstancesOnDeploymentSuccess?: BlueInstanceTerminationOption|array|null,
     *   deploymentReadyOption?: DeploymentReadyOption|array|null,
     *   greenFleetProvisioningOption?: GreenFleetProvisioningOption|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->terminateBlueInstancesOnDeploymentSuccess = isset($input['terminateBlueInstancesOnDeploymentSuccess']) ? BlueInstanceTerminationOption::create($input['terminateBlueInstancesOnDeploymentSuccess']) : null;
        $this->deploymentReadyOption = isset($input['deploymentReadyOption']) ? DeploymentReadyOption::create($input['deploymentReadyOption']) : null;
        $this->greenFleetProvisioningOption = isset($input['greenFleetProvisioningOption']) ? GreenFleetProvisioningOption::create($input['greenFleetProvisioningOption']) : null;
    }

    /**
     * @param array{
     *   terminateBlueInstancesOnDeploymentSuccess?: BlueInstanceTerminationOption|array|null,
     *   deploymentReadyOption?: DeploymentReadyOption|array|null,
     *   greenFleetProvisioningOption?: GreenFleetProvisioningOption|array|null,
     * }|BlueGreenDeploymentConfiguration $input
     */
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
