<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the deployment status of the instances in the deployment.
 */
final class DeploymentOverview
{
    /**
     * The number of instances in the deployment in a pending state.
     *
     * @var int|null
     */
    private $pending;

    /**
     * The number of instances in which the deployment is in progress.
     *
     * @var int|null
     */
    private $inProgress;

    /**
     * The number of instances in the deployment to which revisions have been successfully deployed.
     *
     * @var int|null
     */
    private $succeeded;

    /**
     * The number of instances in the deployment in a failed state.
     *
     * @var int|null
     */
    private $failed;

    /**
     * The number of instances in the deployment in a skipped state.
     *
     * @var int|null
     */
    private $skipped;

    /**
     * The number of instances in a replacement environment ready to receive traffic in a blue/green deployment.
     *
     * @var int|null
     */
    private $ready;

    /**
     * @param array{
     *   Pending?: int|null,
     *   InProgress?: int|null,
     *   Succeeded?: int|null,
     *   Failed?: int|null,
     *   Skipped?: int|null,
     *   Ready?: int|null,
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
     *   Pending?: int|null,
     *   InProgress?: int|null,
     *   Succeeded?: int|null,
     *   Failed?: int|null,
     *   Skipped?: int|null,
     *   Ready?: int|null,
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
