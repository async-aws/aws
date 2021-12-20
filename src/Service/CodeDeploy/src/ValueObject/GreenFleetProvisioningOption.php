<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\GreenFleetProvisioningAction;

/**
 * Information about how instances are provisioned for a replacement environment in a blue/green deployment.
 */
final class GreenFleetProvisioningOption
{
    /**
     * The method used to add instances to a replacement environment.
     */
    private $action;

    /**
     * @param array{
     *   action?: null|GreenFleetProvisioningAction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->action = $input['action'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return GreenFleetProvisioningAction::*|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }
}
