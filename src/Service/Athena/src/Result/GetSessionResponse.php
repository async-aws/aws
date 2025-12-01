<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\Enum\EncryptionOption;
use AsyncAws\Athena\Enum\SessionState;
use AsyncAws\Athena\ValueObject\Classification;
use AsyncAws\Athena\ValueObject\CloudWatchLoggingConfiguration;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Athena\ValueObject\ManagedLoggingConfiguration;
use AsyncAws\Athena\ValueObject\MonitoringConfiguration;
use AsyncAws\Athena\ValueObject\S3LoggingConfiguration;
use AsyncAws\Athena\ValueObject\SessionConfiguration;
use AsyncAws\Athena\ValueObject\SessionStatistics;
use AsyncAws\Athena\ValueObject\SessionStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetSessionResponse extends Result
{
    /**
     * The session ID.
     *
     * @var string|null
     */
    private $sessionId;

    /**
     * The session description.
     *
     * @var string|null
     */
    private $description;

    /**
     * The workgroup to which the session belongs.
     *
     * @var string|null
     */
    private $workGroup;

    /**
     * The engine version used by the session (for example, `PySpark engine version 3`). You can get a list of engine
     * versions by calling ListEngineVersions.
     *
     * @var string|null
     */
    private $engineVersion;

    /**
     * Contains engine configuration information like DPU usage.
     *
     * @var EngineConfiguration|null
     */
    private $engineConfiguration;

    /**
     * The notebook version.
     *
     * @var string|null
     */
    private $notebookVersion;

    /**
     * @var MonitoringConfiguration|null
     */
    private $monitoringConfiguration;

    /**
     * Contains the workgroup configuration information used by the session.
     *
     * @var SessionConfiguration|null
     */
    private $sessionConfiguration;

    /**
     * Contains information about the status of the session.
     *
     * @var SessionStatus|null
     */
    private $status;

    /**
     * Contains the DPU execution time.
     *
     * @var SessionStatistics|null
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

    public function getMonitoringConfiguration(): ?MonitoringConfiguration
    {
        $this->initialize();

        return $this->monitoringConfiguration;
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
        $this->monitoringConfiguration = empty($data['MonitoringConfiguration']) ? null : $this->populateResultMonitoringConfiguration($data['MonitoringConfiguration']);
        $this->sessionConfiguration = empty($data['SessionConfiguration']) ? null : $this->populateResultSessionConfiguration($data['SessionConfiguration']);
        $this->status = empty($data['Status']) ? null : $this->populateResultSessionStatus($data['Status']);
        $this->statistics = empty($data['Statistics']) ? null : $this->populateResultSessionStatistics($data['Statistics']);
    }

    private function populateResultClassification(array $json): Classification
    {
        return new Classification([
            'Name' => isset($json['Name']) ? (string) $json['Name'] : null,
            'Properties' => !isset($json['Properties']) ? null : $this->populateResultParametersMap($json['Properties']),
        ]);
    }

    /**
     * @return Classification[]
     */
    private function populateResultClassificationList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultClassification($item);
        }

        return $items;
    }

    private function populateResultCloudWatchLoggingConfiguration(array $json): CloudWatchLoggingConfiguration
    {
        return new CloudWatchLoggingConfiguration([
            'Enabled' => filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'LogGroup' => isset($json['LogGroup']) ? (string) $json['LogGroup'] : null,
            'LogStreamNamePrefix' => isset($json['LogStreamNamePrefix']) ? (string) $json['LogStreamNamePrefix'] : null,
            'LogTypes' => !isset($json['LogTypes']) ? null : $this->populateResultLogTypesMap($json['LogTypes']),
        ]);
    }

    private function populateResultEncryptionConfiguration(array $json): EncryptionConfiguration
    {
        return new EncryptionConfiguration([
            'EncryptionOption' => !EncryptionOption::exists((string) $json['EncryptionOption']) ? EncryptionOption::UNKNOWN_TO_SDK : (string) $json['EncryptionOption'],
            'KmsKey' => isset($json['KmsKey']) ? (string) $json['KmsKey'] : null,
        ]);
    }

    private function populateResultEngineConfiguration(array $json): EngineConfiguration
    {
        return new EngineConfiguration([
            'CoordinatorDpuSize' => isset($json['CoordinatorDpuSize']) ? (int) $json['CoordinatorDpuSize'] : null,
            'MaxConcurrentDpus' => isset($json['MaxConcurrentDpus']) ? (int) $json['MaxConcurrentDpus'] : null,
            'DefaultExecutorDpuSize' => isset($json['DefaultExecutorDpuSize']) ? (int) $json['DefaultExecutorDpuSize'] : null,
            'AdditionalConfigs' => !isset($json['AdditionalConfigs']) ? null : $this->populateResultParametersMap($json['AdditionalConfigs']),
            'SparkProperties' => !isset($json['SparkProperties']) ? null : $this->populateResultParametersMap($json['SparkProperties']),
            'Classifications' => !isset($json['Classifications']) ? null : $this->populateResultClassificationList($json['Classifications']),
        ]);
    }

    /**
     * @return string[]
     */
    private function populateResultLogTypeValuesList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return array<string, string[]>
     */
    private function populateResultLogTypesMap(array $json): array
    {
        $items = [];
        foreach ($json as $name => $value) {
            $items[(string) $name] = $this->populateResultLogTypeValuesList($value);
        }

        return $items;
    }

    private function populateResultManagedLoggingConfiguration(array $json): ManagedLoggingConfiguration
    {
        return new ManagedLoggingConfiguration([
            'Enabled' => filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'KmsKey' => isset($json['KmsKey']) ? (string) $json['KmsKey'] : null,
        ]);
    }

    private function populateResultMonitoringConfiguration(array $json): MonitoringConfiguration
    {
        return new MonitoringConfiguration([
            'CloudWatchLoggingConfiguration' => empty($json['CloudWatchLoggingConfiguration']) ? null : $this->populateResultCloudWatchLoggingConfiguration($json['CloudWatchLoggingConfiguration']),
            'ManagedLoggingConfiguration' => empty($json['ManagedLoggingConfiguration']) ? null : $this->populateResultManagedLoggingConfiguration($json['ManagedLoggingConfiguration']),
            'S3LoggingConfiguration' => empty($json['S3LoggingConfiguration']) ? null : $this->populateResultS3LoggingConfiguration($json['S3LoggingConfiguration']),
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

    private function populateResultS3LoggingConfiguration(array $json): S3LoggingConfiguration
    {
        return new S3LoggingConfiguration([
            'Enabled' => filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'KmsKey' => isset($json['KmsKey']) ? (string) $json['KmsKey'] : null,
            'LogLocation' => isset($json['LogLocation']) ? (string) $json['LogLocation'] : null,
        ]);
    }

    private function populateResultSessionConfiguration(array $json): SessionConfiguration
    {
        return new SessionConfiguration([
            'ExecutionRole' => isset($json['ExecutionRole']) ? (string) $json['ExecutionRole'] : null,
            'WorkingDirectory' => isset($json['WorkingDirectory']) ? (string) $json['WorkingDirectory'] : null,
            'IdleTimeoutSeconds' => isset($json['IdleTimeoutSeconds']) ? (int) $json['IdleTimeoutSeconds'] : null,
            'SessionIdleTimeoutInMinutes' => isset($json['SessionIdleTimeoutInMinutes']) ? (int) $json['SessionIdleTimeoutInMinutes'] : null,
            'EncryptionConfiguration' => empty($json['EncryptionConfiguration']) ? null : $this->populateResultEncryptionConfiguration($json['EncryptionConfiguration']),
        ]);
    }

    private function populateResultSessionStatistics(array $json): SessionStatistics
    {
        return new SessionStatistics([
            'DpuExecutionInMillis' => isset($json['DpuExecutionInMillis']) ? (int) $json['DpuExecutionInMillis'] : null,
        ]);
    }

    private function populateResultSessionStatus(array $json): SessionStatus
    {
        return new SessionStatus([
            'StartDateTime' => (isset($json['StartDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['StartDateTime'])))) ? $d : null,
            'LastModifiedDateTime' => (isset($json['LastModifiedDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['LastModifiedDateTime'])))) ? $d : null,
            'EndDateTime' => (isset($json['EndDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['EndDateTime'])))) ? $d : null,
            'IdleSinceDateTime' => (isset($json['IdleSinceDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['IdleSinceDateTime'])))) ? $d : null,
            'State' => isset($json['State']) ? (!SessionState::exists((string) $json['State']) ? SessionState::UNKNOWN_TO_SDK : (string) $json['State']) : null,
            'StateChangeReason' => isset($json['StateChangeReason']) ? (string) $json['StateChangeReason'] : null,
        ]);
    }
}
