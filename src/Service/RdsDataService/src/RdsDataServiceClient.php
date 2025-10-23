<?php

namespace AsyncAws\RdsDataService;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\RdsDataService\Enum\RecordsFormatType;
use AsyncAws\RdsDataService\Exception\AccessDeniedException;
use AsyncAws\RdsDataService\Exception\BadRequestException;
use AsyncAws\RdsDataService\Exception\DatabaseErrorException;
use AsyncAws\RdsDataService\Exception\DatabaseNotFoundException;
use AsyncAws\RdsDataService\Exception\DatabaseResumingException;
use AsyncAws\RdsDataService\Exception\DatabaseUnavailableException;
use AsyncAws\RdsDataService\Exception\ForbiddenException;
use AsyncAws\RdsDataService\Exception\HttpEndpointNotEnabledException;
use AsyncAws\RdsDataService\Exception\InternalServerErrorException;
use AsyncAws\RdsDataService\Exception\InvalidResourceStateException;
use AsyncAws\RdsDataService\Exception\InvalidSecretException;
use AsyncAws\RdsDataService\Exception\NotFoundException;
use AsyncAws\RdsDataService\Exception\SecretsErrorException;
use AsyncAws\RdsDataService\Exception\ServiceUnavailableErrorException;
use AsyncAws\RdsDataService\Exception\StatementTimeoutException;
use AsyncAws\RdsDataService\Exception\TransactionNotFoundException;
use AsyncAws\RdsDataService\Exception\UnsupportedResultException;
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
use AsyncAws\RdsDataService\ValueObject\ResultSetOptions;
use AsyncAws\RdsDataService\ValueObject\SqlParameter;

class RdsDataServiceClient extends AbstractApi
{
    /**
     * Runs a batch SQL statement over an array of data.
     *
     * You can run bulk update and insert operations for multiple records using a DML statement with different parameter
     * sets. Bulk operations can provide a significant performance improvement over individual insert and update operations.
     *
     * > If a call isn't part of a transaction because it doesn't include the `transactionID` parameter, changes that result
     * > from the call are committed automatically.
     * >
     * > There isn't a fixed upper limit on the number of parameter sets. However, the maximum size of the HTTP request
     * > submitted through the Data API is 4 MiB. If the request exceeds this limit, the Data API returns an error and
     * > doesn't process the request. This 4-MiB limit includes the size of the HTTP headers and the JSON notation in the
     * > request. Thus, the number of parameter sets that you can include depends on a combination of factors, such as the
     * > size of the SQL statement and the size of each parameter set.
     * >
     * > The response size limit is 1 MiB. If the call returns more than 1 MiB of response data, the call is terminated.
     *
     * @see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_BatchExecuteStatement.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#batchexecutestatement
     *
     * @param array{
     *   resourceArn: string,
     *   secretArn: string,
     *   sql: string,
     *   database?: string|null,
     *   schema?: string|null,
     *   parameterSets?: array[]|null,
     *   transactionId?: string|null,
     *   '@region'?: string|null,
     * }|BatchExecuteStatementRequest $input
     *
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws DatabaseErrorException
     * @throws DatabaseNotFoundException
     * @throws DatabaseResumingException
     * @throws DatabaseUnavailableException
     * @throws ForbiddenException
     * @throws HttpEndpointNotEnabledException
     * @throws InternalServerErrorException
     * @throws InvalidResourceStateException
     * @throws InvalidSecretException
     * @throws SecretsErrorException
     * @throws ServiceUnavailableErrorException
     * @throws StatementTimeoutException
     * @throws TransactionNotFoundException
     */
    public function batchExecuteStatement($input): BatchExecuteStatementResponse
    {
        $input = BatchExecuteStatementRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BatchExecuteStatement', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'BadRequestException' => BadRequestException::class,
            'DatabaseErrorException' => DatabaseErrorException::class,
            'DatabaseNotFoundException' => DatabaseNotFoundException::class,
            'DatabaseResumingException' => DatabaseResumingException::class,
            'DatabaseUnavailableException' => DatabaseUnavailableException::class,
            'ForbiddenException' => ForbiddenException::class,
            'HttpEndpointNotEnabledException' => HttpEndpointNotEnabledException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'InvalidResourceStateException' => InvalidResourceStateException::class,
            'InvalidSecretException' => InvalidSecretException::class,
            'SecretsErrorException' => SecretsErrorException::class,
            'ServiceUnavailableError' => ServiceUnavailableErrorException::class,
            'StatementTimeoutException' => StatementTimeoutException::class,
            'TransactionNotFoundException' => TransactionNotFoundException::class,
        ]]));

        return new BatchExecuteStatementResponse($response);
    }

    /**
     * Starts a SQL transaction.
     *
     * > A transaction can run for a maximum of 24 hours. A transaction is terminated and rolled back automatically after 24
     * > hours.
     * >
     * > A transaction times out if no calls use its transaction ID in three minutes. If a transaction times out before it's
     * > committed, it's rolled back automatically.
     * >
     * > For Aurora MySQL, DDL statements inside a transaction cause an implicit commit. We recommend that you run each
     * > MySQL DDL statement in a separate `ExecuteStatement` call with `continueAfterTimeout` enabled.
     *
     * @see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_BeginTransaction.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#begintransaction
     *
     * @param array{
     *   resourceArn: string,
     *   secretArn: string,
     *   database?: string|null,
     *   schema?: string|null,
     *   '@region'?: string|null,
     * }|BeginTransactionRequest $input
     *
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws DatabaseErrorException
     * @throws DatabaseNotFoundException
     * @throws DatabaseResumingException
     * @throws DatabaseUnavailableException
     * @throws ForbiddenException
     * @throws HttpEndpointNotEnabledException
     * @throws InternalServerErrorException
     * @throws InvalidResourceStateException
     * @throws InvalidSecretException
     * @throws SecretsErrorException
     * @throws ServiceUnavailableErrorException
     * @throws StatementTimeoutException
     * @throws TransactionNotFoundException
     */
    public function beginTransaction($input): BeginTransactionResponse
    {
        $input = BeginTransactionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'BeginTransaction', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'BadRequestException' => BadRequestException::class,
            'DatabaseErrorException' => DatabaseErrorException::class,
            'DatabaseNotFoundException' => DatabaseNotFoundException::class,
            'DatabaseResumingException' => DatabaseResumingException::class,
            'DatabaseUnavailableException' => DatabaseUnavailableException::class,
            'ForbiddenException' => ForbiddenException::class,
            'HttpEndpointNotEnabledException' => HttpEndpointNotEnabledException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'InvalidResourceStateException' => InvalidResourceStateException::class,
            'InvalidSecretException' => InvalidSecretException::class,
            'SecretsErrorException' => SecretsErrorException::class,
            'ServiceUnavailableError' => ServiceUnavailableErrorException::class,
            'StatementTimeoutException' => StatementTimeoutException::class,
            'TransactionNotFoundException' => TransactionNotFoundException::class,
        ]]));

        return new BeginTransactionResponse($response);
    }

    /**
     * Ends a SQL transaction started with the `BeginTransaction` operation and commits the changes.
     *
     * @see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_CommitTransaction.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#committransaction
     *
     * @param array{
     *   resourceArn: string,
     *   secretArn: string,
     *   transactionId: string,
     *   '@region'?: string|null,
     * }|CommitTransactionRequest $input
     *
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws DatabaseErrorException
     * @throws DatabaseNotFoundException
     * @throws DatabaseUnavailableException
     * @throws ForbiddenException
     * @throws HttpEndpointNotEnabledException
     * @throws InternalServerErrorException
     * @throws InvalidResourceStateException
     * @throws InvalidSecretException
     * @throws NotFoundException
     * @throws SecretsErrorException
     * @throws ServiceUnavailableErrorException
     * @throws StatementTimeoutException
     * @throws TransactionNotFoundException
     */
    public function commitTransaction($input): CommitTransactionResponse
    {
        $input = CommitTransactionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CommitTransaction', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'BadRequestException' => BadRequestException::class,
            'DatabaseErrorException' => DatabaseErrorException::class,
            'DatabaseNotFoundException' => DatabaseNotFoundException::class,
            'DatabaseUnavailableException' => DatabaseUnavailableException::class,
            'ForbiddenException' => ForbiddenException::class,
            'HttpEndpointNotEnabledException' => HttpEndpointNotEnabledException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'InvalidResourceStateException' => InvalidResourceStateException::class,
            'InvalidSecretException' => InvalidSecretException::class,
            'NotFoundException' => NotFoundException::class,
            'SecretsErrorException' => SecretsErrorException::class,
            'ServiceUnavailableError' => ServiceUnavailableErrorException::class,
            'StatementTimeoutException' => StatementTimeoutException::class,
            'TransactionNotFoundException' => TransactionNotFoundException::class,
        ]]));

        return new CommitTransactionResponse($response);
    }

    /**
     * Runs a SQL statement against a database.
     *
     * > If a call isn't part of a transaction because it doesn't include the `transactionID` parameter, changes that result
     * > from the call are committed automatically.
     * >
     * > If the binary response data from the database is more than 1 MB, the call is terminated.
     *
     * @see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_ExecuteStatement.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#executestatement
     *
     * @param array{
     *   resourceArn: string,
     *   secretArn: string,
     *   sql: string,
     *   database?: string|null,
     *   schema?: string|null,
     *   parameters?: array<SqlParameter|array>|null,
     *   transactionId?: string|null,
     *   includeResultMetadata?: bool|null,
     *   continueAfterTimeout?: bool|null,
     *   resultSetOptions?: ResultSetOptions|array|null,
     *   formatRecordsAs?: RecordsFormatType::*|null,
     *   '@region'?: string|null,
     * }|ExecuteStatementRequest $input
     *
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws DatabaseErrorException
     * @throws DatabaseNotFoundException
     * @throws DatabaseResumingException
     * @throws DatabaseUnavailableException
     * @throws ForbiddenException
     * @throws HttpEndpointNotEnabledException
     * @throws InternalServerErrorException
     * @throws InvalidResourceStateException
     * @throws InvalidSecretException
     * @throws SecretsErrorException
     * @throws ServiceUnavailableErrorException
     * @throws StatementTimeoutException
     * @throws TransactionNotFoundException
     * @throws UnsupportedResultException
     */
    public function executeStatement($input): ExecuteStatementResponse
    {
        $input = ExecuteStatementRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ExecuteStatement', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'BadRequestException' => BadRequestException::class,
            'DatabaseErrorException' => DatabaseErrorException::class,
            'DatabaseNotFoundException' => DatabaseNotFoundException::class,
            'DatabaseResumingException' => DatabaseResumingException::class,
            'DatabaseUnavailableException' => DatabaseUnavailableException::class,
            'ForbiddenException' => ForbiddenException::class,
            'HttpEndpointNotEnabledException' => HttpEndpointNotEnabledException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'InvalidResourceStateException' => InvalidResourceStateException::class,
            'InvalidSecretException' => InvalidSecretException::class,
            'SecretsErrorException' => SecretsErrorException::class,
            'ServiceUnavailableError' => ServiceUnavailableErrorException::class,
            'StatementTimeoutException' => StatementTimeoutException::class,
            'TransactionNotFoundException' => TransactionNotFoundException::class,
            'UnsupportedResultException' => UnsupportedResultException::class,
        ]]));

        return new ExecuteStatementResponse($response);
    }

    /**
     * Performs a rollback of a transaction. Rolling back a transaction cancels its changes.
     *
     * @see https://docs.aws.amazon.com/rdsdataservice/latest/APIReference/API_RollbackTransaction.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-rds-data-2018-08-01.html#rollbacktransaction
     *
     * @param array{
     *   resourceArn: string,
     *   secretArn: string,
     *   transactionId: string,
     *   '@region'?: string|null,
     * }|RollbackTransactionRequest $input
     *
     * @throws AccessDeniedException
     * @throws BadRequestException
     * @throws DatabaseErrorException
     * @throws DatabaseNotFoundException
     * @throws DatabaseUnavailableException
     * @throws ForbiddenException
     * @throws HttpEndpointNotEnabledException
     * @throws InternalServerErrorException
     * @throws InvalidResourceStateException
     * @throws InvalidSecretException
     * @throws NotFoundException
     * @throws SecretsErrorException
     * @throws ServiceUnavailableErrorException
     * @throws StatementTimeoutException
     * @throws TransactionNotFoundException
     */
    public function rollbackTransaction($input): RollbackTransactionResponse
    {
        $input = RollbackTransactionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'RollbackTransaction', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'BadRequestException' => BadRequestException::class,
            'DatabaseErrorException' => DatabaseErrorException::class,
            'DatabaseNotFoundException' => DatabaseNotFoundException::class,
            'DatabaseUnavailableException' => DatabaseUnavailableException::class,
            'ForbiddenException' => ForbiddenException::class,
            'HttpEndpointNotEnabledException' => HttpEndpointNotEnabledException::class,
            'InternalServerErrorException' => InternalServerErrorException::class,
            'InvalidResourceStateException' => InvalidResourceStateException::class,
            'InvalidSecretException' => InvalidSecretException::class,
            'NotFoundException' => NotFoundException::class,
            'SecretsErrorException' => SecretsErrorException::class,
            'ServiceUnavailableError' => ServiceUnavailableErrorException::class,
            'StatementTimeoutException' => StatementTimeoutException::class,
            'TransactionNotFoundException' => TransactionNotFoundException::class,
        ]]));

        return new RollbackTransactionResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://rds-data-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://rds-data-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://rds-data-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://rds-data-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'rds-data',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://rds-data.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'rds-data',
            'signVersions' => ['v4'],
        ];
    }
}
