<?php

namespace AsyncAws\Athena\Tests\Integration;

use AsyncAws\Athena\AthenaClient;
use AsyncAws\Athena\Input\GetCalculationExecutionRequest;
use AsyncAws\Athena\Input\GetCalculationExecutionStatusRequest;
use AsyncAws\Athena\Input\GetDatabaseInput;
use AsyncAws\Athena\Input\GetDataCatalogInput;
use AsyncAws\Athena\Input\GetNamedQueryInput;
use AsyncAws\Athena\Input\GetQueryExecutionInput;
use AsyncAws\Athena\Input\GetQueryResultsInput;
use AsyncAws\Athena\Input\GetSessionRequest;
use AsyncAws\Athena\Input\GetSessionStatusRequest;
use AsyncAws\Athena\Input\GetTableMetadataInput;
use AsyncAws\Athena\Input\GetWorkGroupInput;
use AsyncAws\Athena\Input\ListDatabasesInput;
use AsyncAws\Athena\Input\ListNamedQueriesInput;
use AsyncAws\Athena\Input\ListQueryExecutionsInput;
use AsyncAws\Athena\Input\ListTableMetadataInput;
use AsyncAws\Athena\Input\StartCalculationExecutionRequest;
use AsyncAws\Athena\Input\StartQueryExecutionInput;
use AsyncAws\Athena\Input\StartSessionRequest;
use AsyncAws\Athena\Input\StopCalculationExecutionRequest;
use AsyncAws\Athena\Input\StopQueryExecutionInput;
use AsyncAws\Athena\Input\TerminateSessionRequest;
use AsyncAws\Athena\ValueObject\AclConfiguration;
use AsyncAws\Athena\ValueObject\CalculationConfiguration;
use AsyncAws\Athena\ValueObject\EncryptionConfiguration;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseByAgeConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseConfiguration;
use AsyncAws\Athena\ValueObject\ResultSet;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;

class AthenaClientTest extends TestCase
{
    public function testGetCalculationExecution(): void
    {
        $client = $this->getClient();

        $input = new GetCalculationExecutionRequest([
            'CalculationExecutionId' => 'change me',
        ]);
        $result = $client->getCalculationExecution($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getCalculationExecutionId());
        self::assertSame('changeIt', $result->getSessionId());
        self::assertSame('changeIt', $result->getDescription());
        self::assertSame('changeIt', $result->getWorkingDirectory());
        // self::assertTODO(expected, $result->getStatus());
        // self::assertTODO(expected, $result->getStatistics());
        // self::assertTODO(expected, $result->getResult());
    }

    public function testGetCalculationExecutionStatus(): void
    {
        $client = $this->getClient();

        $input = new GetCalculationExecutionStatusRequest([
            'CalculationExecutionId' => 'change me',
        ]);
        $result = $client->getCalculationExecutionStatus($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getStatus());
        // self::assertTODO(expected, $result->getStatistics());
    }

    public function testGetDataCatalog(): void
    {
        $client = $this->getClient();

        $input = new GetDataCatalogInput([
            'Name' => 'change me',
        ]);
        $result = $client->getDataCatalog($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getDataCatalog());
    }

    public function testGetDatabase(): void
    {
        $client = $this->getClient();

        $input = new GetDatabaseInput([
            'CatalogName' => 'change me',
            'DatabaseName' => 'change me',
        ]);
        $result = $client->getDatabase($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getDatabase());
    }

    public function testGetNamedQuery(): void
    {
        $client = $this->getClient();

        $input = new GetNamedQueryInput([
            'NamedQueryId' => 'change me',
        ]);
        $result = $client->getNamedQuery($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getNamedQuery());
    }

    public function testGetQueryExecution(): void
    {
        $client = $this->getClient();

        $input = new GetQueryExecutionInput([
            'QueryExecutionId' => 'change me',
        ]);
        $result = $client->getQueryExecution($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getQueryExecution());
    }

    public function testGetQueryResults(): void
    {
        $client = $this->getClient();

        $input = new GetQueryResultsInput([
            'QueryExecutionId' => 'change me',
            'NextToken' => 'change me',
            'MaxResults' => 1337,
        ]);
        $result = $client->getQueryResults($input);

        $result->resolve();

        self::assertSame(1337, $result->getUpdateCount());
        self::assertInstanceOf(ResultSet::class, $result->getResultSet());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testGetSession(): void
    {
        $client = $this->getClient();

        $input = new GetSessionRequest([
            'SessionId' => 'change me',
        ]);
        $result = $client->getSession($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getSessionId());
        self::assertSame('changeIt', $result->getDescription());
        self::assertSame('changeIt', $result->getWorkGroup());
        self::assertSame('changeIt', $result->getEngineVersion());
        // self::assertTODO(expected, $result->getEngineConfiguration());
        self::assertSame('changeIt', $result->getNotebookVersion());
        // self::assertTODO(expected, $result->getSessionConfiguration());
        // self::assertTODO(expected, $result->getStatus());
        // self::assertTODO(expected, $result->getStatistics());
    }

    public function testGetSessionStatus(): void
    {
        $client = $this->getClient();

        $input = new GetSessionStatusRequest([
            'SessionId' => 'change me',
        ]);
        $result = $client->getSessionStatus($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getSessionId());
        // self::assertTODO(expected, $result->getStatus());
    }

    public function testGetTableMetadata(): void
    {
        $client = $this->getClient();

        $input = new GetTableMetadataInput([
            'CatalogName' => 'change me',
            'DatabaseName' => 'change me',
            'TableName' => 'change me',
        ]);
        $result = $client->getTableMetadata($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getTableMetadata());
    }

    public function testGetWorkGroup(): void
    {
        $client = $this->getClient();

        $input = new GetWorkGroupInput([
            'WorkGroup' => 'change me',
        ]);
        $result = $client->getWorkGroup($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getWorkGroup());
    }

    public function testListDatabases(): void
    {
        $client = $this->getClient();

        $input = new ListDatabasesInput([
            'CatalogName' => 'change me',
            'NextToken' => 'change me',
            'MaxResults' => 1337,
        ]);
        $result = $client->listDatabases($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getDatabaseList());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testListNamedQueries(): void
    {
        $client = $this->getClient();

        $input = new ListNamedQueriesInput([
            'NextToken' => 'change me',
            'MaxResults' => 1337,
            'WorkGroup' => 'change me',
        ]);
        $result = $client->listNamedQueries($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getNamedQueryIds());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testListQueryExecutions(): void
    {
        $client = $this->getClient();

        $input = new ListQueryExecutionsInput([
            'NextToken' => 'change me',
            'MaxResults' => 1337,
            'WorkGroup' => 'change me',
        ]);
        $result = $client->listQueryExecutions($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getQueryExecutionIds());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testListTableMetadata(): void
    {
        $client = $this->getClient();

        $input = new ListTableMetadataInput([
            'CatalogName' => 'change me',
            'DatabaseName' => 'change me',
            'Expression' => 'change me',
            'NextToken' => 'change me',
            'MaxResults' => 1337,
        ]);
        $result = $client->listTableMetadata($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getTableMetadataList());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testStartCalculationExecution(): void
    {
        $client = $this->getClient();

        $input = new StartCalculationExecutionRequest([
            'SessionId' => 'change me',
            'Description' => 'change me',
            'CalculationConfiguration' => new CalculationConfiguration([
                'CodeBlock' => 'change me',
            ]),
            'CodeBlock' => 'change me',
            'ClientRequestToken' => 'change me',
        ]);
        $result = $client->startCalculationExecution($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getCalculationExecutionId());
        self::assertSame('changeIt', $result->getState());
    }

    public function testStartQueryExecution(): void
    {
        $client = $this->getClient();

        $input = new StartQueryExecutionInput([
            'QueryString' => 'select * from my-table limit 3',
            'ClientRequestToken' => 'Unique-caSe-sensitive-0011',
            'QueryExecutionContext' => new QueryExecutionContext([
                'Database' => 'my_dbname',
                'Catalog' => 'my_catalog_name',
            ]),
            'ResultConfiguration' => new ResultConfiguration([
                'OutputLocation' => 's3://my_bucket/',
                'EncryptionConfiguration' => new EncryptionConfiguration([
                    'EncryptionOption' => 'SSE_S3',
                ]),
                'ExpectedBucketOwner' => 's3_bucket_owner',
                'AclConfiguration' => new AclConfiguration([
                    'S3AclOption' => 'BUCKET_OWNER_FULL_CONTROL',
                ]),
            ]),
            'WorkGroup' => 'my_worgroup_name',
            'ExecutionParameters' => [],
            'ResultReuseConfiguration' => new ResultReuseConfiguration([
                'ResultReuseByAgeConfiguration' => new ResultReuseByAgeConfiguration([
                    'Enabled' => false,
                    'MaxAgeInMinutes' => 1337,
                ]),
            ]),
        ]);
        $result = $client->startQueryExecution($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getQueryExecutionId());
    }

    public function testStartSession(): void
    {
        $client = $this->getClient();

        $input = new StartSessionRequest([
            'Description' => 'my test description',
            'WorkGroup' => 'my_workgroup_name',
            'EngineConfiguration' => new EngineConfiguration([
                'CoordinatorDpuSize' => 1337,
                'MaxConcurrentDpus' => 1337,
                'DefaultExecutorDpuSize' => 1337,
                'AdditionalConfigs' => ['change me' => 'change me'],
            ]),
            'NotebookVersion' => 'change me',
            'SessionIdleTimeoutInMinutes' => 1337,
            'ClientRequestToken' => 'change me',
        ]);
        $result = $client->startSession($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getSessionId());
        self::assertSame('changeIt', $result->getState());
    }

    public function testStopCalculationExecution(): void
    {
        $client = $this->getClient();

        $input = new StopCalculationExecutionRequest([
            'CalculationExecutionId' => 'change me',
        ]);
        $result = $client->stopCalculationExecution($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getState());
    }

    public function testStopQueryExecution(): void
    {
        $client = $this->getClient();

        $input = new StopQueryExecutionInput([
            'QueryExecutionId' => 'change me',
        ]);
        $result = $client->stopQueryExecution($input);

        $result->resolve();
    }

    public function testTerminateSession(): void
    {
        $client = $this->getClient();

        $input = new TerminateSessionRequest([
            'SessionId' => 'change me',
        ]);
        $result = $client->terminateSession($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getState());
    }

    private function getClient(): AthenaClient
    {
        self::markTestSkipped('No yet Docker image for Athena ');

        return new AthenaClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
