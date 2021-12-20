<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * A summary of the deployment status of the instances in the deployment.
 */
final class DeploymentOverview
{
    /**
     * The number of instances in the deployment in a pending state.
     */
    private $pending;

    /**
     * The number of instances in which the deployment is in progress.
     */
    private $inProgress;

    /**
     * The number of instances in the deployment to which revisions have been successfully deployed.
     */
    private $succeeded;

    /**
     * The number of instances in the deployment in a failed state.
     */
    private $failed;

    /**
     * The number of instances in the deployment in a skipped state.
     */
    private $skipped;

    /**
     * The number of instances in a replacement environment ready to receive traffic in a blue/green deployment.
     */
    private $ready;

    /**
     * @param array{
     *   Pending?: null|string,
     *   InProgress?: null|string,
     *   Succeeded?: null|string,
     *   Failed?: null|string,
     *   Skipped?: null|string,
     *   Ready?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->pending = $input['Pending'] ?? null;
        $this->inProgress = $input['InProgress'] ?? null;
        $this->succeeded = $input['Succeeded'] ?? null;
        $this->failed = $input['Failed'] ?? null;
        $this->skipped = $input['Skipped'] ?? null;
        $this->ready = $input['Ready'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFailed(): ?string
    {
        return $this->failed;
    }

    public function getInProgress(): ?string
    {
        return $this->inProgress;
    }

    public function getPending(): ?string
    {
        return $this->pending;
    }

    public function getReady(): ?string
    {
        return $this->ready;
    }

    public function getSkipped(): ?string
    {
        return $this->skipped;
    }

    public function getSucceeded(): ?string
    {
        return $this->succeeded;
    }
}
