<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the deployment status of the instances in the deployment.
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
     *   Pending?: null|int,
     *   InProgress?: null|int,
     *   Succeeded?: null|int,
     *   Failed?: null|int,
     *   Skipped?: null|int,
     *   Ready?: null|int,
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

    /**
     * @param array{
     *   Pending?: null|int,
     *   InProgress?: null|int,
     *   Succeeded?: null|int,
     *   Failed?: null|int,
     *   Skipped?: null|int,
     *   Ready?: null|int,
     * }|DeploymentOverview $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFailed(): ?int
    {
        return $this->failed;
    }

    public function getInProgress(): ?int
    {
        return $this->inProgress;
    }

    public function getPending(): ?int
    {
        return $this->pending;
    }

    public function getReady(): ?int
    {
        return $this->ready;
    }

    public function getSkipped(): ?int
    {
        return $this->skipped;
    }

    public function getSucceeded(): ?int
    {
        return $this->succeeded;
    }
}
