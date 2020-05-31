<?php

namespace AsyncAws\RdsDataService\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Input\BatchExecuteStatementRequest;
use AsyncAws\RdsDataService\Input\BeginTransactionRequest;
use AsyncAws\RdsDataService\Input\CommitTransactionRequest;
use AsyncAws\RdsDataService\Input\ExecuteStatementRequest;
use AsyncAws\RdsDataService\Input\RollbackTransactionRequest;
use AsyncAws\RdsDataService\RdsDataServiceClient;
use AsyncAws\RdsDataService\ValueObject\ResultSetOptions;
use AsyncAws\RdsDataService\ValueObject\SqlParameter;

class RdsDataServiceClientTest extends TestCase
{
    public function testBatchExecuteStatement(): void
    {
        $client = $this->getClient();

        $input = new BatchExecuteStatementRequest([
            'database' => 'my_database',
            'parameterSets' => [
                [
                    new SqlParameter(['name' => 'name', 'value' => ['stringValue' => 'Max']]),
                ],
            ],
            'resourceArn' => 'arn:resource',
            'secretArn' => 'arn:secret',
            'sql' => 'SELECT * FROM ',
        ]);
        $result = $client->BatchExecuteStatement($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getupdateResults());
    }

    public function testBeginTransaction(): void
    {
        $client = $this->getClient();

        $input = new BeginTransactionRequest([
            'database' => 'change me',
            'resourceArn' => 'change me',
            'schema' => 'change me',
            'secretArn' => 'change me',
        ]);
        $result = $client->BeginTransaction($input);

        $result->resolve();

        self::assertSame('changeIt', $result->gettransactionId());
    }

    public function testCommitTransaction(): void
    {
        $client = $this->getClient();

        $input = new CommitTransactionRequest([
            'resourceArn' => 'change me',
            'secretArn' => 'change me',
            'transactionId' => 'change me',
        ]);
        $result = $client->CommitTransaction($input);

        $result->resolve();

        self::assertSame('changeIt', $result->gettransactionStatus());
    }

    public function testExecuteStatement(): void
    {
        $client = $this->getClient();

        $input = new ExecuteStatementRequest([
            'continueAfterTimeout' => false,
            'database' => 'change me',
            'includeResultMetadata' => false,
            'parameters' => [new SqlParameter([
                'name' => 'change me',
                'typeHint' => 'change me',
                'value' => [
                    'a' => 'b',
                ],
            ])],
            'resourceArn' => 'change me',
            'resultSetOptions' => new ResultSetOptions([
                'decimalReturnType' => 'change me',
            ]),
            'schema' => 'change me',
            'secretArn' => 'change me',
            'sql' => 'change me',
            'transactionId' => 'change me',
        ]);
        $result = $client->ExecuteStatement($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getcolumnMetadata());
        // self::assertTODO(expected, $result->getgeneratedFields());
        self::assertSame(1337, $result->getnumberOfRecordsUpdated());
        // self::assertTODO(expected, $result->getrecords());
    }

    public function testRollbackTransaction(): void
    {
        $client = $this->getClient();

        $input = new RollbackTransactionRequest([
            'resourceArn' => 'change me',
            'secretArn' => 'change me',
            'transactionId' => 'change me',
        ]);
        $result = $client->RollbackTransaction($input);

        $result->resolve();

        self::assertSame('changeIt', $result->gettransactionStatus());
    }

    private function getClient(): RdsDataServiceClient
    {
        self::markTestSkipped('Not implemented');

        return new RdsDataServiceClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
