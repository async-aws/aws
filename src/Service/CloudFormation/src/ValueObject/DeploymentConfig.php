<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\DeploymentConfigMode;

/**
 * The deployment configuration for a stack operation, including the deployment mode.
 */
final class DeploymentConfig
{
    /**
     * Specifies the deployment mode for the stack operation. Possible values are:
     *
     * - `STANDARD` - Use the standard deployment behavior, ensuring resources are ready to serve traffic before completing
     *   the operation. This is the default. You do not need to specify this value explicitly.
     * - `EXPRESS` - Complete the stack operation when resource configuration is applied, without waiting for resources to
     *   become ready to serve traffic. Resources continue becoming ready in the background.
     *
     * @var DeploymentConfigMode::*|null
     */
    private $mode;

    /**
     * Specifies whether to disable rollback of the stack if the stack operation fails.
     *
     * Default: `false`
     *
     * @var bool|null
     */
    private $disableRollback;

    /**
     * @param array{
     *   Mode?: DeploymentConfigMode::*|null,
     *   DisableRollback?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mode = $input['Mode'] ?? null;
        $this->disableRollback = $input['DisableRollback'] ?? null;
    }

    /**
     * @param array{
     *   Mode?: DeploymentConfigMode::*|null,
     *   DisableRollback?: bool|null,
     * }|DeploymentConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisableRollback(): ?bool
    {
        return $this->disableRollback;
    }

    /**
     * @return DeploymentConfigMode::*|null
     */
    public function getMode(): ?string
    {
        return $this->mode;
    }
}
