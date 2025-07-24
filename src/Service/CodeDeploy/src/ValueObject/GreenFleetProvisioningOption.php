<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\GreenFleetProvisioningAction;

/**
 * Information about the instances that belong to the replacement environment in a blue/green deployment.
 */
final class GreenFleetProvisioningOption
{
    /**
     * The method used to add instances to a replacement environment.
     *
     * - `DISCOVER_EXISTING`: Use instances that already exist or will be created manually.
     * - `COPY_AUTO_SCALING_GROUP`: Use settings from a specified Auto Scaling group to define and create instances in a new
     *   Auto Scaling group.
     *
     * @var GreenFleetProvisioningAction::*|string|null
     */
    private $action;

    /**
     * @param array{
     *   action?: null|GreenFleetProvisioningAction::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->action = $input['action'] ?? null;
    }

    /**
     * @param array{
     *   action?: null|GreenFleetProvisioningAction::*|string,
     * }|GreenFleetProvisioningOption $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GreenFleetProvisioningAction::*|string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }
}
