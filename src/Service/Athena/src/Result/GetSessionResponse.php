<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Athena\ValueObject\SessionConfiguration;
use AsyncAws\Athena\ValueObject\SessionStatistics;
use AsyncAws\Athena\ValueObject\SessionStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetSessionResponse extends Result
{
    /**
     * The session ID.
     */
    private $sessionId;

    /**
     * The session description.
     */
    private $description;

    /**
     * The workgroup to which the session belongs.
     */
    private $workGroup;

    /**
     * The engine version used by the session (for example, `PySpark engine version 3`). You can get a list of engine
     * versions by calling ListEngineVersions.
     */
    private $engineVersion;

    /**
     * Contains engine configuration information like DPU usage.
     */
    private $engineConfiguration;

    /**
     * The notebook version.
     */
    private $notebookVersion;

    /**
     * Contains the workgroup configuration information used by the session.
     */
    private $sessionConfiguration;

    /**
     * Contains information about the status of the session.
     */
    private $status;

    /**
     * Contains the DPU execution time.
     */
    private $statistics;

    public function getDescription(): ?string
    {
        $this->initialize();

        return $this->description;
    }

    public function getEngineConfiguration(): ?EngineConfiguration
    {
        $this->initialize();

        return $this->engineConfiguration;
    }

    public function getEngineVersion(): ?string
    {
        $this->initialize();

        return $this->engineVersion;
    }

    public function getNotebookVersion(): ?string
    {
        $this->initialize();

        return $this->notebookVersion;
    }

    public function getSessionConfiguration(): ?SessionConfiguration
    {
        $this->initialize();

        return $this->sessionConfiguration;
    }

    public function getSessionId(): ?string
    {
        $this->initialize();

        return $this->sessionId;
    }

    public function getStatistics(): ?SessionStatistics
    {
        $this->initialize();

        return $this->statistics;
    }

    public function getStatus(): ?SessionStatus
    {
        $this->initialize();

        return $this->status;
    }

    public function getWorkGroup(): ?string
    {
        $this->initialize();

        return $this->workGroup;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->sessionId = isset($data['SessionId']) ? (string) $data['SessionId'] : null;
        $this->description = isset($data['Description']) ? (string) $data['Description'] : null;
        $this->workGroup = isset($data['WorkGroup']) ? (string) $data['WorkGroup'] : null;
        $this->engineVersion = isset($data['EngineVersion']) ? (string) $data['EngineVersion'] : null;
        $this->engineConfiguration = empty($data['EngineConfiguration']) ? null : $this->populateResultEngineConfiguration($data['EngineConfiguration']);
        $this->notebookVersion = isset($data['NotebookVersion']) ? (string) $data['NotebookVersion'] : null;
        $this->sessionConfiguration = empty($data['SessionConfiguration']) ? null : $this->populateResultSessionConfiguration($data['SessionConfiguration']);
        $this->status = empty($data['Status']) ? null : $this->populateResultSessionStatus($data['Status']);
        $this->statistics = empty($data['Statistics']) ? null : $this->populateResultSessionStatistics($data['Statistics']);
    }

    private function populateResultEncryptionConfiguration(array $json): EncryptionConfiguration
    {
        return new EncryptionConfiguration([
            'EncryptionOption' => (string) $json['EncryptionOption'],
            'KmsKey' => isset($json['KmsKey']) ? (string) $json['KmsKey'] : null,
        ]);
    }

    private function populateResultEngineConfiguration(array $json): EngineConfiguration
    {
        return new EngineConfiguration([
            'CoordinatorDpuSize' => isset($json['CoordinatorDpuSize']) ? (int) $json['CoordinatorDpuSize'] : null,
            'MaxConcurrentDpus' => (int) $json['MaxConcurrentDpus'],
            'DefaultExecutorDpuSize' => isset($json['DefaultExecutorDpuSize']) ? (int) $json['DefaultExecutorDpuSize'] : null,
            'AdditionalConfigs' => !isset($json['AdditionalConfigs']) ? null : $this->populateResultParametersMap($json['AdditionalConfigs']),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function populateResultParametersMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = (string) $value;
        }

        return $items;
    }

    private function populateResultSessionConfiguration(array $json): SessionConfiguration
    {
        return new SessionConfiguration([
            'ExecutionRole' => isset($json['ExecutionRole']) ? (string) $json['ExecutionRole'] : null,
            'WorkingDirectory' => isset($json['WorkingDirectory']) ? (string) $json['WorkingDirectory'] : null,
            'IdleTimeoutSeconds' => isset($json['IdleTimeoutSeconds']) ? (string) $json['IdleTimeoutSeconds'] : null,
            'EncryptionConfiguration' => empty($json['EncryptionConfiguration']) ? null : $this->populateResultEncryptionConfiguration($json['EncryptionConfiguration']),
        ]);
    }

    private function populateResultSessionStatistics(array $json): SessionStatistics
    {
        return new SessionStatistics([
            'DpuExecutionInMillis' => isset($json['DpuExecutionInMillis']) ? (string) $json['DpuExecutionInMillis'] : null,
        ]);
    }

    private function populateResultSessionStatus(array $json): SessionStatus
    {
        return new SessionStatus([
            'StartDateTime' => (isset($json['StartDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['StartDateTime'])))) ? $d : null,
            'LastModifiedDateTime' => (isset($json['LastModifiedDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['LastModifiedDateTime'])))) ? $d : null,
            'EndDateTime' => (isset($json['EndDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['EndDateTime'])))) ? $d : null,
            'IdleSinceDateTime' => (isset($json['IdleSinceDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['IdleSinceDateTime'])))) ? $d : null,
            'State' => isset($json['State']) ? (string) $json['State'] : null,
            'StateChangeReason' => isset($json['StateChangeReason']) ? (string) $json['StateChangeReason'] : null,
        ]);
    }
}
