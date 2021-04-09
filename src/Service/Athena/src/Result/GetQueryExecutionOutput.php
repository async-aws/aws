<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EngineVersion;
use AsyncAws\Athena\ValueObject\QueryExecution;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\QueryExecutionStatistics;
use AsyncAws\Athena\ValueObject\QueryExecutionStatus;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetQueryExecutionOutput extends Result
{
    /**
     * Information about the query execution.
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

        $this->queryExecution = empty($data['QueryExecution']) ? null : new QueryExecution([
            'QueryExecutionId' => isset($data['QueryExecution']['QueryExecutionId']) ? (string) $data['QueryExecution']['QueryExecutionId'] : null,
            'Query' => isset($data['QueryExecution']['Query']) ? (string) $data['QueryExecution']['Query'] : null,
            'StatementType' => isset($data['QueryExecution']['StatementType']) ? (string) $data['QueryExecution']['StatementType'] : null,
            'ResultConfiguration' => empty($data['QueryExecution']['ResultConfiguration']) ? null : new ResultConfiguration([
                'OutputLocation' => isset($data['QueryExecution']['ResultConfiguration']['OutputLocation']) ? (string) $data['QueryExecution']['ResultConfiguration']['OutputLocation'] : null,
                'EncryptionConfiguration' => empty($data['QueryExecution']['ResultConfiguration']['EncryptionConfiguration']) ? null : new EncryptionConfiguration([
                    'EncryptionOption' => (string) $data['QueryExecution']['ResultConfiguration']['EncryptionConfiguration']['EncryptionOption'],
                    'KmsKey' => isset($data['QueryExecution']['ResultConfiguration']['EncryptionConfiguration']['KmsKey']) ? (string) $data['QueryExecution']['ResultConfiguration']['EncryptionConfiguration']['KmsKey'] : null,
                ]),
            ]),
            'QueryExecutionContext' => empty($data['QueryExecution']['QueryExecutionContext']) ? null : new QueryExecutionContext([
                'Database' => isset($data['QueryExecution']['QueryExecutionContext']['Database']) ? (string) $data['QueryExecution']['QueryExecutionContext']['Database'] : null,
                'Catalog' => isset($data['QueryExecution']['QueryExecutionContext']['Catalog']) ? (string) $data['QueryExecution']['QueryExecutionContext']['Catalog'] : null,
            ]),
            'Status' => empty($data['QueryExecution']['Status']) ? null : new QueryExecutionStatus([
                'State' => isset($data['QueryExecution']['Status']['State']) ? (string) $data['QueryExecution']['Status']['State'] : null,
                'StateChangeReason' => isset($data['QueryExecution']['Status']['StateChangeReason']) ? (string) $data['QueryExecution']['Status']['StateChangeReason'] : null,
                'SubmissionDateTime' => (isset($data['QueryExecution']['Status']['SubmissionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['QueryExecution']['Status']['SubmissionDateTime'])))) ? $d : null,
                'CompletionDateTime' => (isset($data['QueryExecution']['Status']['CompletionDateTime']) && ($d = \DateTimeImmutable::createFromFormat('U.u', \sprintf('%.6F', $data['QueryExecution']['Status']['CompletionDateTime'])))) ? $d : null,
            ]),
            'Statistics' => empty($data['QueryExecution']['Statistics']) ? null : new QueryExecutionStatistics([
                'EngineExecutionTimeInMillis' => isset($data['QueryExecution']['Statistics']['EngineExecutionTimeInMillis']) ? (string) $data['QueryExecution']['Statistics']['EngineExecutionTimeInMillis'] : null,
                'DataScannedInBytes' => isset($data['QueryExecution']['Statistics']['DataScannedInBytes']) ? (string) $data['QueryExecution']['Statistics']['DataScannedInBytes'] : null,
                'DataManifestLocation' => isset($data['QueryExecution']['Statistics']['DataManifestLocation']) ? (string) $data['QueryExecution']['Statistics']['DataManifestLocation'] : null,
                'TotalExecutionTimeInMillis' => isset($data['QueryExecution']['Statistics']['TotalExecutionTimeInMillis']) ? (string) $data['QueryExecution']['Statistics']['TotalExecutionTimeInMillis'] : null,
                'QueryQueueTimeInMillis' => isset($data['QueryExecution']['Statistics']['QueryQueueTimeInMillis']) ? (string) $data['QueryExecution']['Statistics']['QueryQueueTimeInMillis'] : null,
                'QueryPlanningTimeInMillis' => isset($data['QueryExecution']['Statistics']['QueryPlanningTimeInMillis']) ? (string) $data['QueryExecution']['Statistics']['QueryPlanningTimeInMillis'] : null,
                'ServiceProcessingTimeInMillis' => isset($data['QueryExecution']['Statistics']['ServiceProcessingTimeInMillis']) ? (string) $data['QueryExecution']['Statistics']['ServiceProcessingTimeInMillis'] : null,
            ]),
            'WorkGroup' => isset($data['QueryExecution']['WorkGroup']) ? (string) $data['QueryExecution']['WorkGroup'] : null,
            'EngineVersion' => empty($data['QueryExecution']['EngineVersion']) ? null : new EngineVersion([
                'SelectedEngineVersion' => isset($data['QueryExecution']['EngineVersion']['SelectedEngineVersion']) ? (string) $data['QueryExecution']['EngineVersion']['SelectedEngineVersion'] : null,
                'EffectiveEngineVersion' => isset($data['QueryExecution']['EngineVersion']['EffectiveEngineVersion']) ? (string) $data['QueryExecution']['EngineVersion']['EffectiveEngineVersion'] : null,
            ]),
        ]);
    }
}
