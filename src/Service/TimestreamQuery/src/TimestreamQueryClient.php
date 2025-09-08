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
use AsyncAws\TimestreamQuery\Input\DescribeEndpointsRequest;
use AsyncAws\TimestreamQuery\Input\PrepareQueryRequest;
use AsyncAws\TimestreamQuery\Input\QueryRequest;
use AsyncAws\TimestreamQuery\Result\CancelQueryResponse;
use AsyncAws\TimestreamQuery\Result\DescribeEndpointsResponse;
use AsyncAws\TimestreamQuery\Result\PrepareQueryResponse;
use AsyncAws\TimestreamQuery\Result\QueryResponse;
use AsyncAws\TimestreamQuery\ValueObject\QueryInsights;

class TimestreamQueryClient extends AbstractApi
{
    /**
     * Cancels a query that has been issued. Cancellation is provided only if the query has not completed running before the
     * cancellation request was issued. Because cancellation is an idempotent operation, subsequent cancellation requests
     * will return a `CancellationMessage`, indicating that the query has already been canceled. See code sample [^1] for
     * details.
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/code-samples.cancel-query.html
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_CancelQuery.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-query.timestream-2018-11-01.html#cancelquery
     *
     * @param array{
     *   QueryId: string,
     *   '@region'?: string|null,
     * }|CancelQueryRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws InvalidEndpointException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function cancelQuery($input): CancelQueryResponse
    {
        $input = CancelQueryRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CancelQuery', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'InvalidEndpointException' => InvalidEndpointException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ], 'requiresEndpointDiscovery' => true, 'usesEndpointDiscovery' => true]));

        return new CancelQueryResponse($response);
    }

    /**
     * DescribeEndpoints returns a list of available endpoints to make Timestream API calls against. This API is available
     * through both Write and Query.
     *
     * Because the Timestream SDKs are designed to transparently work with the service’s architecture, including the
     * management and mapping of the service endpoints, *it is not recommended that you use this API unless*:
     *
     * - You are using VPC endpoints (Amazon Web Services PrivateLink) with Timestream [^1]
     * - Your application uses a programming language that does not yet have SDK support
     * - You require better control over the client-side implementation
     *
     * For detailed information on how and when to use and implement DescribeEndpoints, see The Endpoint Discovery Pattern
     * [^2].
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/VPCEndpoints
     * [^2]: https://docs.aws.amazon.com/timestream/latest/developerguide/Using.API.html#Using-API.endpoint-discovery
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_DescribeEndpoints.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-query.timestream-2018-11-01.html#describeendpoints
     *
     * @param array{
     *   '@region'?: string|null,
     * }|DescribeEndpointsRequest $input
     *
     * @throws InternalServerException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function describeEndpoints($input = []): DescribeEndpointsResponse
    {
        $input = DescribeEndpointsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DescribeEndpoints', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new DescribeEndpointsResponse($response);
    }

    /**
     * A synchronous operation that allows you to submit a query with parameters to be stored by Timestream for later
     * running. Timestream only supports using this operation with `ValidateOnly` set to `true`.
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_PrepareQuery.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-query.timestream-2018-11-01.html#preparequery
     *
     * @param array{
     *   QueryString: string,
     *   ValidateOnly?: bool|null,
     *   '@region'?: string|null,
     * }|PrepareQueryRequest $input
     *
     * @throws AccessDeniedException
     * @throws InternalServerException
     * @throws InvalidEndpointException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function prepareQuery($input): PrepareQueryResponse
    {
        $input = PrepareQueryRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PrepareQuery', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'InternalServerException' => InternalServerException::class,
            'InvalidEndpointException' => InvalidEndpointException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ], 'requiresEndpointDiscovery' => true, 'usesEndpointDiscovery' => true]));

        return new PrepareQueryResponse($response);
    }

    /**
     * `Query` is a synchronous operation that enables you to run a query against your Amazon Timestream data.
     *
     * If you enabled `QueryInsights`, this API also returns insights and metrics related to the query that you executed.
     * `QueryInsights` helps with performance tuning of your query. For more information about `QueryInsights`, see Using
     * query insights to optimize queries in Amazon Timestream [^1].
     *
     * > The maximum number of `Query` API requests you're allowed to make with `QueryInsights` enabled is 1 query per
     * > second (QPS). If you exceed this query rate, it might result in throttling.
     *
     * `Query` will time out after 60 seconds. You must update the default timeout in the SDK to support a timeout of 60
     * seconds. See the code sample [^2] for details.
     *
     * Your query request will fail in the following cases:
     *
     * - If you submit a `Query` request with the same client token outside of the 5-minute idempotency window.
     * - If you submit a `Query` request with the same client token, but change other parameters, within the 5-minute
     *   idempotency window.
     * - If the size of the row (including the query metadata) exceeds 1 MB, then the query will fail with the following
     *   error message:
     *
     *   `Query aborted as max page response size has been exceeded by the output result row`
     * - If the IAM principal of the query initiator and the result reader are not the same and/or the query initiator and
     *   the result reader do not have the same query string in the query requests, the query will fail with an `Invalid
     *   pagination token` error.
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/using-query-insights.html
     * [^2]: https://docs.aws.amazon.com/timestream/latest/developerguide/code-samples.run-query.html
     *
     * @see https://docs.aws.amazon.com/timestream/latest/developerguide/API_Query.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-query.timestream-2018-11-01.html#query
     *
     * @param array{
     *   QueryString: string,
     *   ClientToken?: string|null,
     *   NextToken?: string|null,
     *   MaxRows?: int|null,
     *   QueryInsights?: QueryInsights|array|null,
     *   '@region'?: string|null,
     * }|QueryRequest $input
     *
     * @throws AccessDeniedException
     * @throws ConflictException
     * @throws InternalServerException
     * @throws InvalidEndpointException
     * @throws QueryExecutionException
     * @throws ThrottlingException
     * @throws ValidationException
     */
    public function query($input): QueryResponse
    {
        $input = QueryRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Query', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AccessDeniedException' => AccessDeniedException::class,
            'ConflictException' => ConflictException::class,
            'InternalServerException' => InternalServerException::class,
            'InvalidEndpointException' => InvalidEndpointException::class,
            'QueryExecutionException' => QueryExecutionException::class,
            'ThrottlingException' => ThrottlingException::class,
            'ValidationException' => ValidationException::class,
        ], 'requiresEndpointDiscovery' => true, 'usesEndpointDiscovery' => true]));

        return new QueryResponse($response, $this, $input);
    }

    protected function discoverEndpoints(?string $region): array
    {
        return $this->describeEndpoints($region ? ['@region' => $region] : [])->getEndpoints();
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
