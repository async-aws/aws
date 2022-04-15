<?php

namespace AsyncAws\TimestreamQuery;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\TimestreamQuery\Exception\AccessDeniedException;
use AsyncAws\TimestreamQuery\Exception\ConflictException;
use AsyncAws\TimestreamQuery\Exception\InternalServerException;
use AsyncAws\TimestreamQuery\Exception\InvalidEndpointException;
use AsyncAws\TimestreamQuery\Exception\QueryExecutionException;
use AsyncAws\TimestreamQuery\Exception\ThrottlingException;
use AsyncAws\TimestreamQuery\Exception\ValidationException;
use AsyncAws\TimestreamQuery\Input\CancelQueryRequest;
use AsyncAws\TimestreamQuery\Input\PrepareQueryRequest;
use AsyncAws\TimestreamQuery\Input\QueryRequest;
use AsyncAws\TimestreamQuery\Result\CancelQueryResponse;
use AsyncAws\TimestreamQuery\Result\PrepareQueryResponse;
use AsyncAws\TimestreamQuery\Result\QueryResponse;

class TimestreamQueryClient extends AbstractApi
{
    /**
     * Cancels a query that has been issued. Cancellation is provided only if the query has not completed running before the
     * cancellation request was issued. Because cancellation is an idempotent operation, subsequent cancellation requests
     * will return a `CancellationMessage`, indicating that the query has already been canceled. See code sample for
     * details.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/code-samples.cancel-query.html
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Query.html/API_CancelQuery.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-query.timestream-2018-11-01.html#cancelquery
     *
     * @param array{
     *   QueryId: string,
     *   @region?: string,
     * }|CancelQueryRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ThrottlingException
     * @throws ValidationException
     * @throws InvalidEndpointException
     */
    public function cancelQuery($input): CancelQueryResponse
    {
        $input = CancelQueryRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CancelQuery', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
            'InvalidEndpointException' => InvalidEndpointException::class,
        ]]));

        return new CancelQueryResponse($response);
    }

    /**
     * A synchronous operation that allows you to submit a query with parameters to be stored by Timestream for later
     * running. Timestream only supports using this operation with the `PrepareQueryRequest$ValidateOnly` set to `true`.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Query.html/API_PrepareQuery.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-query.timestream-2018-11-01.html#preparequery
     *
     * @param array{
     *   QueryString: string,
     *   ValidateOnly?: bool,
     *   @region?: string,
     * }|PrepareQueryRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws ThrottlingException
     * @throws ValidationException
     * @throws InvalidEndpointException
     */
    public function prepareQuery($input): PrepareQueryResponse
    {
        $input = PrepareQueryRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PrepareQuery', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
            'InvalidEndpointException' => InvalidEndpointException::class,
        ]]));

        return new PrepareQueryResponse($response);
    }

    /**
     * `Query` is a synchronous operation that enables you to run a query against your Amazon Timestream data. `Query` will
     * time out after 60 seconds. You must update the default timeout in the SDK to support a timeout of 60 seconds. See the
     * code sample for details.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/code-samples.run-query.html
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Operations_Amazon_Timestream_Query.html/API_Query.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-query.timestream-2018-11-01.html#query
     *
     * @param array{
     *   QueryString: string,
     *   ClientToken?: string,
     *   NextToken?: string,
     *   MaxRows?: int,
     *   @region?: string,
     * }|QueryRequest $input
     *
     * @throws AccessDeniedException
     * @throws ConflictException
     * @throws InternalServerException
     * @throws QueryExecutionException
     * @throws ThrottlingException
     * @throws ValidationException
     * @throws InvalidEndpointException
     */
    public function query($input): QueryResponse
    {
        $input = QueryRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Query', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'QueryExecutionException' => QueryExecutionException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
            'InvalidEndpointException' => InvalidEndpointException::class,
        ]]));

        return new QueryResponse($response, $this, $input);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        return [
            'endpoint' => "https://query.timestream.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'timestream',
            'signVersions' => ['v4'],
        ];
    }
}
