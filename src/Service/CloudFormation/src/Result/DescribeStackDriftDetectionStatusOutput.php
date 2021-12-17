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
     */
    private $stackId;

    /**
     * The ID of the drift detection results of this operation.
     */
    private $stackDriftDetectionId;

    /**
     * Status of the stack's actual configuration compared to its expected configuration.
     */
    private $stackDriftStatus;

    /**
     * The status of the stack drift detection operation.
     */
    private $detectionStatus;

    /**
     * The reason the stack drift detection operation has its current status.
     */
    private $detectionStatusReason;

    /**
     * Total number of stack resources that have drifted. This is NULL until the drift detection operation reaches a status
     * of `DETECTION_COMPLETE`. This value will be 0 for stacks whose drift status is `IN_SYNC`.
     */
    private $driftedStackResourceCount;

    /**
     * Time at which the stack drift detection operation was initiated.
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
