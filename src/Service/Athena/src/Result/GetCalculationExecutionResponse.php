<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\CalculationExecutionState;
use AsyncAws\Athena\ValueObject\CalculationResult;
use AsyncAws\Athena\ValueObject\CalculationStatistics;
use AsyncAws\Athena\ValueObject\CalculationStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetCalculationExecutionResponse extends Result
{
    /**
     * The calculation execution UUID.
     *
     * @var string|null
     */
    private $calculationExecutionId;

    /**
     * The session ID that the calculation ran in.
     *
     * @var string|null
     */
    private $sessionId;

    /**
     * The description of the calculation execution.
     *
     * @var string|null
     */
    private $description;

    /**
     * The Amazon S3 location in which calculation results are stored.
     *
     * @var string|null
     */
    private $workingDirectory;

    /**
     * Contains information about the status of the calculation.
     *
     * @var CalculationStatus|null
     */
    private $status;

    /**
     * Contains information about the data processing unit (DPU) execution time and progress. This field is populated only
     * when statistics are available.
     *
     * @var CalculationStatistics|null
     */
    private $statistics;

    /**
     * Contains result information. This field is populated only if the calculation is completed.
     *
     * @var CalculationResult|null
     */
    private $result;

    public function getCalculationExecutionId(): ?string
    {
        $this->initialize();

        return $this->calculationExecutionId;
    }

    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->description;
    }

    public function getResult(): ?CalculationResult
    {
        $this->initialize();

        return $this->result;
    }

    public function getSessionId(): ?string
    {
        $this->initialize();

        return $this->sessionId;
    }

    public function getStatistics(): ?CalculationStatistics
    {
        $this->initialize();

        return $this->statistics;
    }

    public function getStatus(): ?CalculationStatus
    {
        $this->initialize();

        return $this->status;
    }

    public function getWorkingDirectory(): ?string
    {
        $this->initialize();

        return $this->workingDirectory;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->calculationExecutionId = isset($data['CalculationExecutionId']) ? (string) $data['CalculationExecutionId'] : null;
        $this->sessionId = isset($data['SessionId']) ? (string) $data['SessionId'] : null;
        $this->description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->workingDirectory = isset($data['WorkingDirectory']) ? (string) $data['WorkingDirectory'] : null;
        $this->status = empty($data['Status']) ? null : $this->populateResultCalculationStatus($data['Status']);
        $this->statistics = empty($data['Statistics']) ? null : $this->populateResultCalculationStatistics($data['Statistics']);
        $this->result = empty($data['Result']) ? null : $this->populateResultCalculationResult($data['Result']);
    }

    private function populateResultCalculationResult(array $json): CalculationResult
    {
        return new CalculationResult([
            'StdOutS3Uri' => isset($json['StdOutS3Uri']) ? (string) $json['StdOutS3Uri'] : null,
            'StdErrorS3Uri' => isset($json['StdErrorS3Uri']) ? (string) $json['StdErrorS3Uri'] : null,
            'ResultS3Uri' => isset($json['ResultS3Uri']) ? (string) $json['ResultS3Uri'] : null,
            'ResultType' => isset($json['ResultType']) ? (string) $json['ResultType'] : null,
        ]);
    }

    private function populateResultCalculationStatistics(array $json): CalculationStatistics
    {
        return new CalculationStatistics([
            'DpuExecutionInMillis' => isset($json['DpuExecutionInMillis']) ? (int) $json['DpuExecutionInMillis'] : null,
            'Progress' => isset($json['Progress']) ? (string) $json['Progress'] : null,
        ]);
    }

    private function populateResultCalculationStatus(array $json): CalculationStatus
    {
        return new CalculationStatus([
            'SubmissionDateTime' => (isset($json['SubmissionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['SubmissionDateTime'])))) ? $d : null,
            'CompletionDateTime' => (isset($json['CompletionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CompletionDateTime'])))) ? $d : null,
            'State' => isset($json['State']) ? (!CalculationExecutionState::exists((string) $json['State']) ? CalculationExecutionState::UNKNOWN_TO_SDK : (string) $json['State']) : null,
            'StateChangeReason' => isset($json['StateChangeReason']) ? (string) $json['StateChangeReason'] : null,
        ]);
    }
}
