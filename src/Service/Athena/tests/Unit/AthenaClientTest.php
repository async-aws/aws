<?php

namespace AsyncAws\Athena\Tests\Unit;

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
use AsyncAws\Athena\Result\GetCalculationExecutionResponse;
use AsyncAws\Athena\Result\GetCalculationExecutionStatusResponse;
use AsyncAws\Athena\Result\GetDatabaseOutput;
use AsyncAws\Athena\Result\GetDataCatalogOutput;
use AsyncAws\Athena\Result\GetNamedQueryOutput;
use AsyncAws\Athena\Result\GetQueryExecutionOutput;
use AsyncAws\Athena\Result\GetQueryResultsOutput;
use AsyncAws\Athena\Result\GetSessionResponse;
use AsyncAws\Athena\Result\GetSessionStatusResponse;
use AsyncAws\Athena\Result\GetTableMetadataOutput;
use AsyncAws\Athena\Result\GetWorkGroupOutput;
use AsyncAws\Athena\Result\ListDatabasesOutput;
use AsyncAws\Athena\Result\ListNamedQueriesOutput;
use AsyncAws\Athena\Result\ListQueryExecutionsOutput;
use AsyncAws\Athena\Result\ListTableMetadataOutput;
use AsyncAws\Athena\Result\StartCalculationExecutionResponse;
use AsyncAws\Athena\Result\StartQueryExecutionOutput;
use AsyncAws\Athena\Result\StartSessionResponse;
use AsyncAws\Athena\Result\StopCalculationExecutionResponse;
use AsyncAws\Athena\Result\StopQueryExecutionOutput;
use AsyncAws\Athena\Result\TerminateSessionResponse;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AthenaClientTest extends TestCase
{
    public function testGetCalculationExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetCalculationExecutionRequest([
            'CalculationExecutionId' => 'iad-6855-254589-ef4r4z5',
        ]);
        $result = $client->getCalculationExecution($input);

        self::assertInstanceOf(GetCalculationExecutionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetCalculationExecutionStatus(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetCalculationExecutionStatusRequest([
            'CalculationExecutionId' => 'iad-6855-254589-ef4r4z5',
        ]);
        $result = $client->getCalculationExecutionStatus($input);

        self::assertInstanceOf(GetCalculationExecutionStatusResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetDataCatalog(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetDataCatalogInput([
            'Name' => 'iadCatalog',
        ]);
        $result = $client->getDataCatalog($input);

        self::assertInstanceOf(GetDataCatalogOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetDatabase(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetDatabaseInput([
            'CatalogName' => 'iadCatalog',
            'DatabaseName' => 'iadDatabase',
        ]);
        $result = $client->getDatabase($input);

        self::assertInstanceOf(GetDatabaseOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetNamedQuery(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetNamedQueryInput([
            'NamedQueryId' => 'iad-query-12536',
        ]);
        $result = $client->getNamedQuery($input);

        self::assertInstanceOf(GetNamedQueryOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetQueryExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetQueryExecutionInput([
            'QueryExecutionId' => 'iad-145r55t-11446',
        ]);
        $result = $client->getQueryExecution($input);

        self::assertInstanceOf(GetQueryExecutionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetQueryResults(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetQueryResultsInput([
            'QueryExecutionId' => 'iad-145r55t-11446',
        ]);
        $result = $client->getQueryResults($input);

        self::assertInstanceOf(GetQueryResultsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetSession(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetSessionRequest([
            'SessionId' => 'session-iad-2563',
        ]);
        $result = $client->getSession($input);

        self::assertInstanceOf(GetSessionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetSessionStatus(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetSessionStatusRequest([
            'SessionId' => 'session-iad-2563',
        ]);
        $result = $client->getSessionStatus($input);

        self::assertInstanceOf(GetSessionStatusResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetTableMetadata(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetTableMetadataInput([
            'CatalogName' => 'iadCatalog',
            'DatabaseName' => 'iadDatabase',
            'TableName' => 'product_catalog',
        ]);
        $result = $client->getTableMetadata($input);

        self::assertInstanceOf(GetTableMetadataOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetWorkGroup(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new GetWorkGroupInput([
            'WorkGroup' => 'iadinternational',
        ]);
        $result = $client->getWorkGroup($input);

        self::assertInstanceOf(GetWorkGroupOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListDatabases(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListDatabasesInput([
            'CatalogName' => 'iadCatalog',
        ]);
        $result = $client->listDatabases($input);

        self::assertInstanceOf(ListDatabasesOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListNamedQueries(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListNamedQueriesInput([
        ]);
        $result = $client->listNamedQueries($input);

        self::assertInstanceOf(ListNamedQueriesOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListQueryExecutions(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListQueryExecutionsInput([
        ]);
        $result = $client->listQueryExecutions($input);

        self::assertInstanceOf(ListQueryExecutionsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListTableMetadata(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new ListTableMetadataInput([
            'CatalogName' => 'iadCatalog',
            'DatabaseName' => 'iadDatabase',
        ]);
        $result = $client->listTableMetadata($input);

        self::assertInstanceOf(ListTableMetadataOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartCalculationExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new StartCalculationExecutionRequest([
            'SessionId' => 'session-iad-2563',
        ]);
        $result = $client->startCalculationExecution($input);

        self::assertInstanceOf(StartCalculationExecutionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartQueryExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new StartQueryExecutionInput([
            'QueryString' => 'SELECT * FROM iadDatabase.catalog LIMT 10',
        ]);
        $result = $client->startQueryExecution($input);

        self::assertInstanceOf(StartQueryExecutionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStartSession(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new StartSessionRequest([
            'WorkGroup' => 'iadinternational',
            'EngineConfiguration' => new EngineConfiguration([
                'MaxConcurrentDpus' => 1337,
            ]),
        ]);
        $result = $client->startSession($input);

        self::assertInstanceOf(StartSessionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStopCalculationExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new StopCalculationExecutionRequest([
            'CalculationExecutionId' => 'iad-e222-4619-ac1f',
        ]);
        $result = $client->stopCalculationExecution($input);

        self::assertInstanceOf(StopCalculationExecutionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testStopQueryExecution(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new StopQueryExecutionInput([
            'QueryExecutionId' => 'irq-iad-25',
        ]);
        $result = $client->stopQueryExecution($input);

        self::assertInstanceOf(StopQueryExecutionOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testTerminateSession(): void
    {
        $client = new AthenaClient([], new NullProvider(), new MockHttpClient());

        $input = new TerminateSessionRequest([
            'SessionId' => 'session-iad-2563',
        ]);
        $result = $client->terminateSession($input);

        self::assertInstanceOf(TerminateSessionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
