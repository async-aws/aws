<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\CalculationExecutionState;
use AsyncAws\Athena\ValueObject\CalculationStatistics;
use AsyncAws\Athena\ValueObject\CalculationStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetCalculationExecutionStatusResponse extends Result
{
    /**
     * Contains information about the calculation execution status.
     *
     * @var CalculationStatus|null
     */
    private $status;

    /**
     * Contains information about the DPU execution time and progress.
     *
     * @var CalculationStatistics|null
     */
    private $statistics;

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

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->status = empty($data['Status']) ? null : $this->populateResultCalculationStatus($data['Status']);
        $this->statistics = empty($data['Statistics']) ? null : $this->populateResultCalculationStatistics($data['Statistics']);
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
