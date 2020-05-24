<?php

namespace AsyncAws\RDSDataService;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\RDSDataService\Input\BatchExecuteStatementRequest;
use AsyncAws\RDSDataService\Input\BeginTransactionRequest;
use AsyncAws\RDSDataService\Input\CommitTransactionRequest;
use AsyncAws\RDSDataService\Input\ExecuteStatementRequest;
use AsyncAws\RDSDataService\Input\RollbackTransactionRequest;
use AsyncAws\RDSDataService\Result\BatchExecuteStatementResponse;
use AsyncAws\RDSDataService\Result\BeginTransactionResponse;
use AsyncAws\RDSDataService\Result\CommitTransactionResponse;
use AsyncAws\RDSDataService\Result\ExecuteStatementResponse;
use AsyncAws\RDSDataService\Result\RollbackTransactionResponse;

class RDSDataServiceClient extends AbstractApi
{
    /**
     * Runs a batch SQL statement over an array of data.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#batchexecutestatement
     *
     * @param array{
     *   database?: string,
     *   parameterSets?: array[],
     *   resourceArn: string,
     *   schema?: string,
     *   secretArn: string,
     *   sql: string,
     *   transactionId?: string,
     *   @region?: string,
     * }|BatchExecuteStatementRequest $input
     */
    public function batchExecuteStatement($input): BatchExecuteStatementResponse
    {
        $input = BatchExecuteStatementRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BatchExecuteStatement', 'region' => $input->getRegion()]));

        return new BatchExecuteStatementResponse($response);
    }

    /**
     * Starts a SQL transaction.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#begintransaction
     *
     * @param array{
     *   database?: string,
     *   resourceArn: string,
     *   schema?: string,
     *   secretArn: string,
     *   @region?: string,
     * }|BeginTransactionRequest $input
     */
    public function beginTransaction($input): BeginTransactionResponse
    {
        $input = BeginTransactionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BeginTransaction', 'region' => $input->getRegion()]));

        return new BeginTransactionResponse($response);
    }

    /**
     * Ends a SQL transaction started with the `BeginTransaction` operation and commits the changes.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#committransaction
     *
     * @param array{
     *   resourceArn: string,
     *   secretArn: string,
     *   transactionId: string,
     *   @region?: string,
     * }|CommitTransactionRequest $input
     */
    public function commitTransaction($input): CommitTransactionResponse
    {
        $input = CommitTransactionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CommitTransaction', 'region' => $input->getRegion()]));

        return new CommitTransactionResponse($response);
    }

    /**
     * Runs a SQL statement against a database.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#executestatement
     *
     * @param array{
     *   continueAfterTimeout?: bool,
     *   database?: string,
     *   includeResultMetadata?: bool,
     *   parameters?: \AsyncAws\RDSDataService\ValueObject\SqlParameter[],
     *   resourceArn: string,
     *   resultSetOptions?: \AsyncAws\RDSDataService\ValueObject\ResultSetOptions|array,
     *   schema?: string,
     *   secretArn: string,
     *   sql: string,
     *   transactionId?: string,
     *   @region?: string,
     * }|ExecuteStatementRequest $input
     */
    public function executeStatement($input): ExecuteStatementResponse
    {
        $input = ExecuteStatementRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ExecuteStatement', 'region' => $input->getRegion()]));

        return new ExecuteStatementResponse($response);
    }

    /**
     * Performs a rollback of a transaction. Rolling back a transaction cancels its changes.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#rollbacktransaction
     *
     * @param array{
     *   resourceArn: string,
     *   secretArn: string,
     *   transactionId: string,
     *   @region?: string,
     * }|RollbackTransactionRequest $input
     */
    public function rollbackTransaction($input): RollbackTransactionResponse
    {
        $input = RollbackTransactionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RollbackTransaction', 'region' => $input->getRegion()]));

        return new RollbackTransactionResponse($response);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        return [
            'endpoint' => "https://rds-data.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'rds-data',
            'signVersions' => ['v4'],
        ];
    }
}
