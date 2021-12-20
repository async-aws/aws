<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about a deployment rollback.
 */
final class RollbackInfo
{
    /**
     * The ID of the deployment rollback.
     */
    private $rollbackDeploymentId;

    /**
     * The deployment ID of the deployment that was underway and triggered a rollback deployment because it failed or was
     * stopped.
     */
    private $rollbackTriggeringDeploymentId;

    /**
     * Information that describes the status of a deployment rollback (for example, whether the deployment can't be rolled
     * back, is in progress, failed, or succeeded).
     */
    private $rollbackMessage;

    /**
     * @param array{
     *   rollbackDeploymentId?: null|string,
     *   rollbackTriggeringDeploymentId?: null|string,
     *   rollbackMessage?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rollbackDeploymentId = $input['rollbackDeploymentId'] ?? null;
        $this->rollbackTriggeringDeploymentId = $input['rollbackTriggeringDeploymentId'] ?? null;
        $this->rollbackMessage = $input['rollbackMessage'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRollbackDeploymentId(): ?string
    {
        return $this->rollbackDeploymentId;
    }

    public function getRollbackMessage(): ?string
    {
        return $this->rollbackMessage;
    }

    public function getRollbackTriggeringDeploymentId(): ?string
    {
        return $this->rollbackTriggeringDeploymentId;
    }
}
