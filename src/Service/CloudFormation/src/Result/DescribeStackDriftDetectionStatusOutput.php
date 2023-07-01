<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\Enum\StackDriftDetectionStatus;
use AsyncAws\CloudFormation\Enum\StackDriftStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DescribeStackDriftDetectionStatusOutput extends Result
{
    /**
     * The ID of the stack.
     *
     * @var string
     */
    private $stackId;

    /**
     * The ID of the drift detection results of this operation.
     *
     * CloudFormation generates new results, with a new drift detection ID, each time this operation is run. However, the
     * number of reports CloudFormation retains for any given stack, and for how long, may vary.
     *
     * @var string
     */
    private $stackDriftDetectionId;

    /**
     * Status of the stack's actual configuration compared to its expected configuration.
     *
     * - `DRIFTED`: The stack differs from its expected template configuration. A stack is considered to have drifted if one
     *   or more of its resources have drifted.
     * - `NOT_CHECKED`: CloudFormation hasn't checked if the stack differs from its expected template configuration.
     * - `IN_SYNC`: The stack's actual configuration matches its expected template configuration.
     * - `UNKNOWN`: This value is reserved for future use.
     *
     * @var StackDriftStatus::*|null
     */
    private $stackDriftStatus;

    /**
     * The status of the stack drift detection operation.
     *
     * - `DETECTION_COMPLETE`: The stack drift detection operation has successfully completed for all resources in the stack
     *   that support drift detection. (Resources that don't currently support stack detection remain unchecked.)
     *
     *   If you specified logical resource IDs for CloudFormation to use as a filter for the stack drift detection
     *   operation, only the resources with those logical IDs are checked for drift.
     * - `DETECTION_FAILED`: The stack drift detection operation has failed for at least one resource in the stack. Results
     *   will be available for resources on which CloudFormation successfully completed drift detection.
     * - `DETECTION_IN_PROGRESS`: The stack drift detection operation is currently in progress.
     *
     * @var StackDriftDetectionStatus::*
     */
    private $detectionStatus;

    /**
     * The reason the stack drift detection operation has its current status.
     *
     * @var string|null
     */
    private $detectionStatusReason;

    /**
     * Total number of stack resources that have drifted. This is NULL until the drift detection operation reaches a status
     * of `DETECTION_COMPLETE`. This value will be 0 for stacks whose drift status is `IN_SYNC`.
     *
     * @var int|null
     */
    private $driftedStackResourceCount;

    /**
     * Time at which the stack drift detection operation was initiated.
     *
     * @var \DateTimeImmutable
     */
    private $timestamp;

    /**
     * @return StackDriftDetectionStatus::*
     */
    public function getDetectionStatus(): string
    {
        $this->initialize();

        return $this->detectionStatus;
    }

    public function getDetectionStatusReason(): ?string
    {
        $this->initialize();

        return $this->detectionStatusReason;
    }

    public function getDriftedStackResourceCount(): ?int
    {
        $this->initialize();

        return $this->driftedStackResourceCount;
    }

    public function getStackDriftDetectionId(): string
    {
        $this->initialize();

        return $this->stackDriftDetectionId;
    }

    /**
     * @return StackDriftStatus::*|null
     */
    public function getStackDriftStatus(): ?string
    {
        $this->initialize();

        return $this->stackDriftStatus;
    }

    public function getStackId(): string
    {
        $this->initialize();

        return $this->stackId;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        $this->initialize();

        return $this->timestamp;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->DescribeStackDriftDetectionStatusResult;

        $this->stackId = (string) $data->StackId;
        $this->stackDriftDetectionId = (string) $data->StackDriftDetectionId;
        $this->stackDriftStatus = ($v = $data->StackDriftStatus) ? (string) $v : null;
        $this->detectionStatus = (string) $data->DetectionStatus;
        $this->detectionStatusReason = ($v = $data->DetectionStatusReason) ? (string) $v : null;
        $this->driftedStackResourceCount = ($v = $data->DriftedStackResourceCount) ? (int) (string) $v : null;
        $this->timestamp = new \DateTimeImmutable((string) $data->Timestamp);
    }
}
