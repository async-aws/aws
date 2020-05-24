<?php

namespace AsyncAws\RDSDataService\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RDSDataService\Input\BatchExecuteStatementRequest;
use AsyncAws\RDSDataService\Input\BeginTransactionRequest;
use AsyncAws\RDSDataService\Input\CommitTransactionRequest;
use AsyncAws\RDSDataService\Input\ExecuteStatementRequest;
use AsyncAws\RDSDataService\Input\RollbackTransactionRequest;
use AsyncAws\RDSDataService\RDSDataServiceClient;
use AsyncAws\RDSDataService\Result\BatchExecuteStatementResponse;
use AsyncAws\RDSDataService\Result\BeginTransactionResponse;
use AsyncAws\RDSDataService\Result\CommitTransactionResponse;
use AsyncAws\RDSDataService\Result\ExecuteStatementResponse;
use AsyncAws\RDSDataService\Result\RollbackTransactionResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class RDSDataServiceClientTest extends TestCase
{
    public function testBatchExecuteStatement(): void
    {
        $client = new RDSDataServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new BatchExecuteStatementRequest([

            'resourceArn' => 'change me',

            'secretArn' => 'change me',
            'sql' => 'change me',

        ]);
        $result = $client->BatchExecuteStatement($input);

        self::assertInstanceOf(BatchExecuteStatementResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testBeginTransaction(): void
    {
        $client = new RDSDataServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new BeginTransactionRequest([

            'resourceArn' => 'change me',

            'secretArn' => 'change me',
        ]);
        $result = $client->BeginTransaction($input);

        self::assertInstanceOf(BeginTransactionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCommitTransaction(): void
    {
        $client = new RDSDataServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new CommitTransactionRequest([
            'resourceArn' => 'change me',
            'secretArn' => 'change me',
            'transactionId' => 'change me',
        ]);
        $result = $client->CommitTransaction($input);

        self::assertInstanceOf(CommitTransactionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testExecuteStatement(): void
    {
        $client = new RDSDataServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new ExecuteStatementRequest([

            'resourceArn' => 'change me',

            'secretArn' => 'change me',
            'sql' => 'change me',

        ]);
        $result = $client->ExecuteStatement($input);

        self::assertInstanceOf(ExecuteStatementResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testRollbackTransaction(): void
    {
        $client = new RDSDataServiceClient([], new NullProvider(), new MockHttpClient());

        $input = new RollbackTransactionRequest([
            'resourceArn' => 'change me',
            'secretArn' => 'change me',
            'transactionId' => 'change me',
        ]);
        $result = $client->RollbackTransaction($input);

        self::assertInstanceOf(RollbackTransactionResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
