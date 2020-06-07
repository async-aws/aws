<?php

namespace AsyncAws\RdsDataService;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\RdsDataService\Input\BatchExecuteStatementRequest;
use AsyncAws\RdsDataService\Input\BeginTransactionRequest;
use AsyncAws\RdsDataService\Input\CommitTransactionRequest;
use AsyncAws\RdsDataService\Input\ExecuteStatementRequest;
use AsyncAws\RdsDataService\Input\RollbackTransactionRequest;
use AsyncAws\RdsDataService\Result\BatchExecuteStatementResponse;
use AsyncAws\RdsDataService\Result\BeginTransactionResponse;
use AsyncAws\RdsDataService\Result\CommitTransactionResponse;
use AsyncAws\RdsDataService\Result\ExecuteStatementResponse;
use AsyncAws\RdsDataService\Result\RollbackTransactionResponse;

class RdsDataServiceClient extends AbstractApi
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
     *   parameters?: \AsyncAws\RdsDataService\ValueObject\SqlParameter[],
     *   resourceArn: string,
     *   resultSetOptions?: \AsyncAws\RdsDataService\ValueObject\ResultSetOptions|array,
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

        switch ($region) {
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => "https://rds-data.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://rds-data.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => "https://rds-data.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => "https://rds-data.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://rds-data.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "RdsDataService".', $region));
    }
}
