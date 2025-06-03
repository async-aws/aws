<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\AclConfiguration;
use AsyncAws\Athena\ValueObject\AthenaError;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EngineVersion;
use AsyncAws\Athena\ValueObject\ManagedQueryResultsConfiguration;
use AsyncAws\Athena\ValueObject\ManagedQueryResultsEncryptionConfiguration;
use AsyncAws\Athena\ValueObject\QueryExecution;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\QueryExecutionStatistics;
use AsyncAws\Athena\ValueObject\QueryExecutionStatus;
use AsyncAws\Athena\ValueObject\QueryResultsS3AccessGrantsConfiguration;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseByAgeConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseInformation;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetQueryExecutionOutput extends Result
{
    /**
     * Information about the query execution.
     *
     * @var QueryExecution|null
     */
    private $queryExecution;

    public function getQueryExecution(): ?QueryExecution
    {
        $this->initialize();

        return $this->queryExecution;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->queryExecution = empty($data['QueryExecution']) ? null : $this->populateResultQueryExecution($data['QueryExecution']);
    }

    private function populateResultAclConfiguration(array $json): AclConfiguration
    {
        return new AclConfiguration([
            'S3AclOption' => (string) $json['S3AclOption'],
        ]);
    }

    private function populateResultAthenaError(array $json): AthenaError
    {
        return new AthenaError([
            'ErrorCategory' => isset($json['ErrorCategory']) ? (int) $json['ErrorCategory'] : null,
            'ErrorType' => isset($json['ErrorType']) ? (int) $json['ErrorType'] : null,
            'Retryable' => isset($json['Retryable']) ? filter_var($json['Retryable'], \FILTER_VALIDATE_BOOLEAN) : null,
            'ErrorMessage' => isset($json['ErrorMessage']) ? (string) $json['ErrorMessage'] : null,
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

    /**
     * @return string[]
     */
    private function populateResultExecutionParameters(array $json): array
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

    private function populateResultQueryExecution(array $json): QueryExecution
    {
        return new QueryExecution([
            'QueryExecutionId' => isset($json['QueryExecutionId']) ? (string) $json['QueryExecutionId'] : null,
            'Query' => isset($json['Query']) ? (string) $json['Query'] : null,
            'StatementType' => isset($json['StatementType']) ? (string) $json['StatementType'] : null,
            'ManagedQueryResultsConfiguration' => empty($json['ManagedQueryResultsConfiguration']) ? null : $this->populateResultManagedQueryResultsConfiguration($json['ManagedQueryResultsConfiguration']),
            'ResultConfiguration' => empty($json['ResultConfiguration']) ? null : $this->populateResultResultConfiguration($json['ResultConfiguration']),
            'ResultReuseConfiguration' => empty($json['ResultReuseConfiguration']) ? null : $this->populateResultResultReuseConfiguration($json['ResultReuseConfiguration']),
            'QueryExecutionContext' => empty($json['QueryExecutionContext']) ? null : $this->populateResultQueryExecutionContext($json['QueryExecutionContext']),
            'Status' => empty($json['Status']) ? null : $this->populateResultQueryExecutionStatus($json['Status']),
            'Statistics' => empty($json['Statistics']) ? null : $this->populateResultQueryExecutionStatistics($json['Statistics']),
            'WorkGroup' => isset($json['WorkGroup']) ? (string) $json['WorkGroup'] : null,
            'EngineVersion' => empty($json['EngineVersion']) ? null : $this->populateResultEngineVersion($json['EngineVersion']),
            'ExecutionParameters' => !isset($json['ExecutionParameters']) ? null : $this->populateResultExecutionParameters($json['ExecutionParameters']),
            'SubstatementType' => isset($json['SubstatementType']) ? (string) $json['SubstatementType'] : null,
            'QueryResultsS3AccessGrantsConfiguration' => empty($json['QueryResultsS3AccessGrantsConfiguration']) ? null : $this->populateResultQueryResultsS3AccessGrantsConfiguration($json['QueryResultsS3AccessGrantsConfiguration']),
        ]);
    }

    private function populateResultQueryExecutionContext(array $json): QueryExecutionContext
    {
        return new QueryExecutionContext([
            'Database' => isset($json['Database']) ? (string) $json['Database'] : null,
            'Catalog' => isset($json['Catalog']) ? (string) $json['Catalog'] : null,
        ]);
    }

    private function populateResultQueryExecutionStatistics(array $json): QueryExecutionStatistics
    {
        return new QueryExecutionStatistics([
            'EngineExecutionTimeInMillis' => isset($json['EngineExecutionTimeInMillis']) ? (int) $json['EngineExecutionTimeInMillis'] : null,
            'DataScannedInBytes' => isset($json['DataScannedInBytes']) ? (int) $json['DataScannedInBytes'] : null,
            'DataManifestLocation' => isset($json['DataManifestLocation']) ? (string) $json['DataManifestLocation'] : null,
            'TotalExecutionTimeInMillis' => isset($json['TotalExecutionTimeInMillis']) ? (int) $json['TotalExecutionTimeInMillis'] : null,
            'QueryQueueTimeInMillis' => isset($json['QueryQueueTimeInMillis']) ? (int) $json['QueryQueueTimeInMillis'] : null,
            'ServicePreProcessingTimeInMillis' => isset($json['ServicePreProcessingTimeInMillis']) ? (int) $json['ServicePreProcessingTimeInMillis'] : null,
            'QueryPlanningTimeInMillis' => isset($json['QueryPlanningTimeInMillis']) ? (int) $json['QueryPlanningTimeInMillis'] : null,
            'ServiceProcessingTimeInMillis' => isset($json['ServiceProcessingTimeInMillis']) ? (int) $json['ServiceProcessingTimeInMillis'] : null,
            'ResultReuseInformation' => empty($json['ResultReuseInformation']) ? null : $this->populateResultResultReuseInformation($json['ResultReuseInformation']),
        ]);
    }

    private function populateResultQueryExecutionStatus(array $json): QueryExecutionStatus
    {
        return new QueryExecutionStatus([
            'State' => isset($json['State']) ? (string) $json['State'] : null,
            'StateChangeReason' => isset($json['StateChangeReason']) ? (string) $json['StateChangeReason'] : null,
            'SubmissionDateTime' => (isset($json['SubmissionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['SubmissionDateTime'])))) ? $d : null,
            'CompletionDateTime' => (isset($json['CompletionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $json['CompletionDateTime'])))) ? $d : null,
            'AthenaError' => empty($json['AthenaError']) ? null : $this->populateResultAthenaError($json['AthenaError']),
        ]);
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

    private function populateResultResultReuseByAgeConfiguration(array $json): ResultReuseByAgeConfiguration
    {
        return new ResultReuseByAgeConfiguration([
            'Enabled' => filter_var($json['Enabled'], \FILTER_VALIDATE_BOOLEAN),
            'MaxAgeInMinutes' => isset($json['MaxAgeInMinutes']) ? (int) $json['MaxAgeInMinutes'] : null,
        ]);
    }

    private function populateResultResultReuseConfiguration(array $json): ResultReuseConfiguration
    {
        return new ResultReuseConfiguration([
            'ResultReuseByAgeConfiguration' => empty($json['ResultReuseByAgeConfiguration']) ? null : $this->populateResultResultReuseByAgeConfiguration($json['ResultReuseByAgeConfiguration']),
        ]);
    }

    private function populateResultResultReuseInformation(array $json): ResultReuseInformation
    {
        return new ResultReuseInformation([
            'ReusedPreviousResult' => filter_var($json['ReusedPreviousResult'], \FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
