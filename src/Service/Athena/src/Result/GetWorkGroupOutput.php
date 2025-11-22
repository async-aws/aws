<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\AclConfiguration;
use AsyncAws\Athena\ValueObject\Classification;
use AsyncAws\Athena\ValueObject\CloudWatchLoggingConfiguration;
use AsyncAws\Athena\ValueObject\CustomerContentEncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Athena\ValueObject\EngineVersion;
use AsyncAws\Athena\ValueObject\IdentityCenterConfiguration;
use AsyncAws\Athena\ValueObject\ManagedLoggingConfiguration;
use AsyncAws\Athena\ValueObject\ManagedQueryResultsConfiguration;
use AsyncAws\Athena\ValueObject\ManagedQueryResultsEncryptionConfiguration;
use AsyncAws\Athena\ValueObject\MonitoringConfiguration;
use AsyncAws\Athena\ValueObject\QueryResultsS3AccessGrantsConfiguration;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\S3LoggingConfiguration;
use AsyncAws\Athena\ValueObject\WorkGroup;
use AsyncAws\Athena\ValueObject\WorkGroupConfiguration;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetWorkGroupOutput extends Result
{
    /**
     * Information about the workgroup.
     *
     * @var WorkGroup|null
     */
    private $workGroup;

    public function getWorkGroup(): ?WorkGroup
    {
        $this->initialize();

        return $this->workGroup;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->workGroup = empty($data['WorkGroup']) ? null : $this->populateResultWorkGroup($data['WorkGroup']);
    }

    private function populateResultAclConfiguration(array $json): AclConfiguration
    {
        return new AclConfiguration([
            'S3AclOption' => (string) $json['S3AclOption'],
        ]);
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

    private function populateResultCustomerContentEncryptionConfiguration(array $json): CustomerContentEncryptionConfiguration
    {
        return new CustomerContentEncryptionConfiguration([
            'KmsKey' => (string) $json['KmsKey'],
        ]);
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
            'MaxConcurrentDpus' => isset($json['MaxConcurrentDpus']) ? (int) $json['MaxConcurrentDpus'] : null,
            'DefaultExecutorDpuSize' => isset($json['DefaultExecutorDpuSize']) ? (int) $json['DefaultExecutorDpuSize'] : null,
            'AdditionalConfigs' => !isset($json['AdditionalConfigs']) ? null : $this->populateResultParametersMap($json['AdditionalConfigs']),
            'SparkProperties' => !isset($json['SparkProperties']) ? null : $this->populateResultParametersMap($json['SparkProperties']),
            'Classifications' => !isset($json['Classifications']) ? null : $this->populateResultClassificationList($json['Classifications']),
        ]);
    }

    private function populateResultEngineVersion(array $json): EngineVersion
    {
        return new EngineVersion([
            'SelectedEngineVersion' => isset($json['SelectedEngineVersion']) ? (string) $json['SelectedEngineVersion'] : null,
            'EffectiveEngineVersion' => isset($json['EffectiveEngineVersion']) ? (string) $json['EffectiveEngineVersion'] : null,
        ]);
    }

    private function populateResultIdentityCenterConfiguration(array $json): IdentityCenterConfiguration
    {
        return new IdentityCenterConfiguration([
            'EnableIdentityCenter' => isset($json['EnableIdentityCenter']) ? filter_var($json['EnableIdentityCenter'], \FILTER_VALIDATE_BOOLEAN) : null,
            'IdentityCenterInstanceArn' => isset($json['IdentityCenterInstanceArn']) ? (string) $json['IdentityCenterInstanceArn'] : null,
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

    private function populateResultManagedQueryResultsConfiguration(array $json): ManagedQueryResultsConfiguration
    {
        return new ManagedQueryResultsConfiguration([
            'Enabled' => filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'EncryptionConfiguration' => empty($json['EncryptionConfiguration']) ? null : $this->populateResultManagedQueryResultsEncryptionConfiguration($json['EncryptionConfiguration']),
        ]);
    }

    private function populateResultManagedQueryResultsEncryptionConfiguration(array $json): ManagedQueryResultsEncryptionConfiguration
    {
        return new ManagedQueryResultsEncryptionConfiguration([
            'KmsKey' => (string) $json['KmsKey'],
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

    private function populateResultQueryResultsS3AccessGrantsConfiguration(array $json): QueryResultsS3AccessGrantsConfiguration
    {
        return new QueryResultsS3AccessGrantsConfiguration([
            'EnableS3AccessGrants' => filter_var($json['EnableS3AccessGrants'], \FILTER_VALIDATE_BOOLEAN),
            'CreateUserLevelPrefix' => isset($json['CreateUserLevelPrefix']) ? filter_var($json['CreateUserLevelPrefix'], \FILTER_VALIDATE_BOOLEAN) : null,
            'AuthenticationType' => (string) $json['AuthenticationType'],
        ]);
    }

    private function populateResultResultConfiguration(array $json): ResultConfiguration
    {
        return new ResultConfiguration([
            'OutputLocation' => isset($json['OutputLocation']) ? (string) $json['OutputLocation'] : null,
            'EncryptionConfiguration' => empty($json['EncryptionConfiguration']) ? null : $this->populateResultEncryptionConfiguration($json['EncryptionConfiguration']),
            'ExpectedBucketOwner' => isset($json['ExpectedBucketOwner']) ? (string) $json['ExpectedBucketOwner'] : null,
            'AclConfiguration' => empty($json['AclConfiguration']) ? null : $this->populateResultAclConfiguration($json['AclConfiguration']),
        ]);
    }

    private function populateResultS3LoggingConfiguration(array $json): S3LoggingConfiguration
    {
        return new S3LoggingConfiguration([
            'Enabled' => filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'KmsKey' => isset($json['KmsKey']) ? (string) $json['KmsKey'] : null,
            'LogLocation' => isset($json['LogLocation']) ? (string) $json['LogLocation'] : null,
        ]);
    }

    private function populateResultWorkGroup(array $json): WorkGroup
    {
        return new WorkGroup([
            'Name' => (string) $json['Name'],
            'State' => isset($json['State']) ? (string) $json['State'] : null,
            'Configuration' => empty($json['Configuration']) ? null : $this->populateResultWorkGroupConfiguration($json['Configuration']),
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'CreationTime' => (isset($json['CreationTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CreationTime'])))) ? $d : null,
            'IdentityCenterApplicationArn' => isset($json['IdentityCenterApplicationArn']) ? (string) $json['IdentityCenterApplicationArn'] : null,
        ]);
    }

    private function populateResultWorkGroupConfiguration(array $json): WorkGroupConfiguration
    {
        return new WorkGroupConfiguration([
            'ResultConfiguration' => empty($json['ResultConfiguration']) ? null : $this->populateResultResultConfiguration($json['ResultConfiguration']),
            'ManagedQueryResultsConfiguration' => empty($json['ManagedQueryResultsConfiguration']) ? null : $this->populateResultManagedQueryResultsConfiguration($json['ManagedQueryResultsConfiguration']),
            'EnforceWorkGroupConfiguration' => isset($json['EnforceWorkGroupConfiguration']) ? filter_var($json['EnforceWorkGroupConfiguration'], \FILTER_VALIDATE_BOOLEAN) : null,
            'PublishCloudWatchMetricsEnabled' => isset($json['PublishCloudWatchMetricsEnabled']) ? filter_var($json['PublishCloudWatchMetricsEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'BytesScannedCutoffPerQuery' => isset($json['BytesScannedCutoffPerQuery']) ? (int) $json['BytesScannedCutoffPerQuery'] : null,
            'RequesterPaysEnabled' => isset($json['RequesterPaysEnabled']) ? filter_var($json['RequesterPaysEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'EngineVersion' => empty($json['EngineVersion']) ? null : $this->populateResultEngineVersion($json['EngineVersion']),
            'AdditionalConfiguration' => isset($json['AdditionalConfiguration']) ? (string) $json['AdditionalConfiguration'] : null,
            'ExecutionRole' => isset($json['ExecutionRole']) ? (string) $json['ExecutionRole'] : null,
            'MonitoringConfiguration' => empty($json['MonitoringConfiguration']) ? null : $this->populateResultMonitoringConfiguration($json['MonitoringConfiguration']),
            'EngineConfiguration' => empty($json['EngineConfiguration']) ? null : $this->populateResultEngineConfiguration($json['EngineConfiguration']),
            'CustomerContentEncryptionConfiguration' => empty($json['CustomerContentEncryptionConfiguration']) ? null : $this->populateResultCustomerContentEncryptionConfiguration($json['CustomerContentEncryptionConfiguration']),
            'EnableMinimumEncryptionConfiguration' => isset($json['EnableMinimumEncryptionConfiguration']) ? filter_var($json['EnableMinimumEncryptionConfiguration'], \FILTER_VALIDATE_BOOLEAN) : null,
            'IdentityCenterConfiguration' => empty($json['IdentityCenterConfiguration']) ? null : $this->populateResultIdentityCenterConfiguration($json['IdentityCenterConfiguration']),
            'QueryResultsS3AccessGrantsConfiguration' => empty($json['QueryResultsS3AccessGrantsConfiguration']) ? null : $this->populateResultQueryResultsS3AccessGrantsConfiguration($json['QueryResultsS3AccessGrantsConfiguration']),
        ]);
    }
}
