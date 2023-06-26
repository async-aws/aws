<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\AclConfiguration;
use AsyncAws\Athena\ValueObject\CustomerContentEncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EngineVersion;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\WorkGroup;
use AsyncAws\Athena\ValueObject\WorkGroupConfiguration;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetWorkGroupOutput extends Result
{
    /**
     * Information about the workgroup.
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

    private function populateResultEngineVersion(array $json): EngineVersion
    {
        return new EngineVersion([
            'SelectedEngineVersion' => isset($json['SelectedEngineVersion']) ? (string) $json['SelectedEngineVersion'] : null,
            'EffectiveEngineVersion' => isset($json['EffectiveEngineVersion']) ? (string) $json['EffectiveEngineVersion'] : null,
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

    private function populateResultWorkGroup(array $json): WorkGroup
    {
        return new WorkGroup([
            'Name' => (string) $json['Name'],
            'State' => isset($json['State']) ? (string) $json['State'] : null,
            'Configuration' => empty($json['Configuration']) ? null : $this->populateResultWorkGroupConfiguration($json['Configuration']),
            'Description' => isset($json['Description']) ? (string) $json['Description'] : null,
            'CreationTime' => (isset($json['CreationTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', sprintf('%.6F', $json['CreationTime'])))) ? $d : null,
        ]);
    }

    private function populateResultWorkGroupConfiguration(array $json): WorkGroupConfiguration
    {
        return new WorkGroupConfiguration([
            'ResultConfiguration' => empty($json['ResultConfiguration']) ? null : $this->populateResultResultConfiguration($json['ResultConfiguration']),
            'EnforceWorkGroupConfiguration' => isset($json['EnforceWorkGroupConfiguration']) ? filter_var($json['EnforceWorkGroupConfiguration'], \FILTER_VALIDATE_BOOLEAN) : null,
            'PublishCloudWatchMetricsEnabled' => isset($json['PublishCloudWatchMetricsEnabled']) ? filter_var($json['PublishCloudWatchMetricsEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'BytesScannedCutoffPerQuery' => isset($json['BytesScannedCutoffPerQuery']) ? (int) $json['BytesScannedCutoffPerQuery'] : null,
            'RequesterPaysEnabled' => isset($json['RequesterPaysEnabled']) ? filter_var($json['RequesterPaysEnabled'], \FILTER_VALIDATE_BOOLEAN) : null,
            'EngineVersion' => empty($json['EngineVersion']) ? null : $this->populateResultEngineVersion($json['EngineVersion']),
            'AdditionalConfiguration' => isset($json['AdditionalConfiguration']) ? (string) $json['AdditionalConfiguration'] : null,
            'ExecutionRole' => isset($json['ExecutionRole']) ? (string) $json['ExecutionRole'] : null,
            'CustomerContentEncryptionConfiguration' => empty($json['CustomerContentEncryptionConfiguration']) ? null : $this->populateResultCustomerContentEncryptionConfiguration($json['CustomerContentEncryptionConfiguration']),
            'EnableMinimumEncryptionConfiguration' => isset($json['EnableMinimumEncryptionConfiguration']) ? filter_var($json['EnableMinimumEncryptionConfiguration'], \FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }
}
