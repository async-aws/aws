<?php

namespace AsyncAws\RdsDataService\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\RdsDataService\Input\BatchExecuteStatementRequest;
use AsyncAws\RdsDataService\Input\BeginTransactionRequest;
use AsyncAws\RdsDataService\Input\CommitTransactionRequest;
use AsyncAws\RdsDataService\Input\ExecuteStatementRequest;
use AsyncAws\RdsDataService\Input\RollbackTransactionRequest;
use AsyncAws\RdsDataService\RdsDataServiceClient;
use AsyncAws\RdsDataService\Result\BatchExecuteStatementResponse;
use AsyncAws\RdsDataService\Result\BeginTransactionResponse;
use AsyncAws\RdsDataService\Result\CommitTransactionResponse;
use AsyncAws\RdsDataService\Result\ExecuteStatementResponse;
use AsyncAws\RdsDataService\Result\RollbackTransactionResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class RdsDataServiceClientTest extends TestCase
{
    public function testBatchExecuteStatement(): void
    {
        $client = new RdsDataServiceClient([], new NullProvider(), new MockHttpClient());

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
        $client = new RdsDataServiceClient([], new NullProvider(), new MockHttpClient());

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
        $client = new RdsDataServiceClient([], new NullProvider(), new MockHttpClient());

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
        $client = new RdsDataServiceClient([], new NullProvider(), new MockHttpClient());

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
        $client = new RdsDataServiceClient([], new NullProvider(), new MockHttpClient());

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
