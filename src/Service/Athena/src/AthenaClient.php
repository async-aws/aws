<?php

namespace AsyncAws\Athena;

use AsyncAws\Athena\Enum\QueryResultType;
use AsyncAws\Athena\Exception\InternalServerException;
use AsyncAws\Athena\Exception\InvalidRequestException;
use AsyncAws\Athena\Exception\MetadataException;
use AsyncAws\Athena\Exception\ResourceNotFoundException;
use AsyncAws\Athena\Exception\SessionAlreadyExistsException;
use AsyncAws\Athena\Exception\TooManyRequestsException;
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
use AsyncAws\Athena\ValueObject\CalculationConfiguration;
use AsyncAws\Athena\ValueObject\EngineConfiguration;
use AsyncAws\Athena\ValueObject\MonitoringConfiguration;
use AsyncAws\Athena\ValueObject\QueryExecutionContext;
use AsyncAws\Athena\ValueObject\ResultConfiguration;
use AsyncAws\Athena\ValueObject\ResultReuseConfiguration;
use AsyncAws\Athena\ValueObject\Tag;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class AthenaClient extends AbstractApi
{
    /**
     * Describes a previously submitted calculation execution.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetCalculationExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getcalculationexecution
     *
     * @param array{
     *   CalculationExecutionId: string,
     *   '@region'?: string|null,
     * }|GetCalculationExecutionRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function getCalculationExecution($input): GetCalculationExecutionResponse
    {
        $input = GetCalculationExecutionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetCalculationExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new GetCalculationExecutionResponse($response);
    }

    /**
     * Gets the status of a current calculation.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetCalculationExecutionStatus.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getcalculationexecutionstatus
     *
     * @param array{
     *   CalculationExecutionId: string,
     *   '@region'?: string|null,
     * }|GetCalculationExecutionStatusRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function getCalculationExecutionStatus($input): GetCalculationExecutionStatusResponse
    {
        $input = GetCalculationExecutionStatusRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetCalculationExecutionStatus', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new GetCalculationExecutionStatusResponse($response);
    }

    /**
     * Returns the specified data catalog.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetDataCatalog.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getdatacatalog
     *
     * @param array{
     *   Name: string,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|GetDataCatalogInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function getDataCatalog($input): GetDataCatalogOutput
    {
        $input = GetDataCatalogInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetDataCatalog', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new GetDataCatalogOutput($response);
    }

    /**
     * Returns a database object for the specified database and data catalog.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetDatabase.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getdatabase
     *
     * @param array{
     *   CatalogName: string,
     *   DatabaseName: string,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|GetDatabaseInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws MetadataException
     */
    public function getDatabase($input): GetDatabaseOutput
    {
        $input = GetDatabaseInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetDatabase', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'MetadataException' => MetadataException::class,
        ]]));

        return new GetDatabaseOutput($response);
    }

    /**
     * Returns information about a single query. Requires that you have access to the workgroup in which the query was
     * saved.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetNamedQuery.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getnamedquery
     *
     * @param array{
     *   NamedQueryId: string,
     *   '@region'?: string|null,
     * }|GetNamedQueryInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function getNamedQuery($input): GetNamedQueryOutput
    {
        $input = GetNamedQueryInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetNamedQuery', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new GetNamedQueryOutput($response);
    }

    /**
     * Returns information about a single execution of a query if you have access to the workgroup in which the query ran.
     * Each time a query executes, information about the query execution is saved with a unique ID.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetQueryExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getqueryexecution
     *
     * @param array{
     *   QueryExecutionId: string,
     *   '@region'?: string|null,
     * }|GetQueryExecutionInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function getQueryExecution($input): GetQueryExecutionOutput
    {
        $input = GetQueryExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueryExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new GetQueryExecutionOutput($response);
    }

    /**
     * Streams the results of a single query execution specified by `QueryExecutionId` from the Athena query results
     * location in Amazon S3. For more information, see Working with query results, recent queries, and output files [^1] in
     * the *Amazon Athena User Guide*. This request does not execute the query but returns results. Use StartQueryExecution
     * to run a query.
     *
     * To stream query results successfully, the IAM principal with permission to call `GetQueryResults` also must have
     * permissions to the Amazon S3 `GetObject` action for the Athena query results location.
     *
     * ! IAM principals with permission to the Amazon S3 `GetObject` action for the query results location are able to
     * ! retrieve query results from Amazon S3 even if permission to the `GetQueryResults` action is denied. To restrict
     * ! user or role access, ensure that Amazon S3 permissions to the Athena query location are denied.
     *
     * [^1]: https://docs.aws.amazon.com/athena/latest/ug/querying.html
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetQueryResults.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getqueryresults
     *
     * @param array{
     *   QueryExecutionId: string,
     *   NextToken?: string|null,
     *   MaxResults?: int|null,
     *   QueryResultType?: QueryResultType::*|null,
     *   '@region'?: string|null,
     * }|GetQueryResultsInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws TooManyRequestsException
     */
    public function getQueryResults($input): GetQueryResultsOutput
    {
        $input = GetQueryResultsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetQueryResults', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new GetQueryResultsOutput($response, $this, $input);
    }

    /**
     * Gets the full details of a previously created session, including the session status and configuration.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetSession.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getsession
     *
     * @param array{
     *   SessionId: string,
     *   '@region'?: string|null,
     * }|GetSessionRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function getSession($input): GetSessionResponse
    {
        $input = GetSessionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSession', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new GetSessionResponse($response);
    }

    /**
     * Gets the current status of a session.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetSessionStatus.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getsessionstatus
     *
     * @param array{
     *   SessionId: string,
     *   '@region'?: string|null,
     * }|GetSessionStatusRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function getSessionStatus($input): GetSessionStatusResponse
    {
        $input = GetSessionStatusRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSessionStatus', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new GetSessionStatusResponse($response);
    }

    /**
     * Returns table metadata for the specified catalog, database, and table.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetTableMetadata.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#gettablemetadata
     *
     * @param array{
     *   CatalogName: string,
     *   DatabaseName: string,
     *   TableName: string,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|GetTableMetadataInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws MetadataException
     */
    public function getTableMetadata($input): GetTableMetadataOutput
    {
        $input = GetTableMetadataInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetTableMetadata', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'MetadataException' => MetadataException::class,
        ]]));

        return new GetTableMetadataOutput($response);
    }

    /**
     * Returns information about the workgroup with the specified name.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_GetWorkGroup.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#getworkgroup
     *
     * @param array{
     *   WorkGroup: string,
     *   '@region'?: string|null,
     * }|GetWorkGroupInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function getWorkGroup($input): GetWorkGroupOutput
    {
        $input = GetWorkGroupInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetWorkGroup', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new GetWorkGroupOutput($response);
    }

    /**
     * Lists the databases in the specified data catalog.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListDatabases.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#listdatabases
     *
     * @param array{
     *   CatalogName: string,
     *   NextToken?: string|null,
     *   MaxResults?: int|null,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|ListDatabasesInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws MetadataException
     */
    public function listDatabases($input): ListDatabasesOutput
    {
        $input = ListDatabasesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListDatabases', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'MetadataException' => MetadataException::class,
        ]]));

        return new ListDatabasesOutput($response, $this, $input);
    }

    /**
     * Provides a list of available query IDs only for queries saved in the specified workgroup. Requires that you have
     * access to the specified workgroup. If a workgroup is not specified, lists the saved queries for the primary
     * workgroup.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListNamedQueries.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#listnamedqueries
     *
     * @param array{
     *   NextToken?: string|null,
     *   MaxResults?: int|null,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|ListNamedQueriesInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function listNamedQueries($input = []): ListNamedQueriesOutput
    {
        $input = ListNamedQueriesInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListNamedQueries', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new ListNamedQueriesOutput($response, $this, $input);
    }

    /**
     * Provides a list of available query execution IDs for the queries in the specified workgroup. Athena keeps a query
     * history for 45 days. If a workgroup is not specified, returns a list of query execution IDs for the primary
     * workgroup. Requires you to have access to the workgroup in which the queries ran.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListQueryExecutions.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#listqueryexecutions
     *
     * @param array{
     *   NextToken?: string|null,
     *   MaxResults?: int|null,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|ListQueryExecutionsInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function listQueryExecutions($input = []): ListQueryExecutionsOutput
    {
        $input = ListQueryExecutionsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListQueryExecutions', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new ListQueryExecutionsOutput($response, $this, $input);
    }

    /**
     * Lists the metadata for the tables in the specified data catalog database.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_ListTableMetadata.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#listtablemetadata
     *
     * @param array{
     *   CatalogName: string,
     *   DatabaseName: string,
     *   Expression?: string|null,
     *   NextToken?: string|null,
     *   MaxResults?: int|null,
     *   WorkGroup?: string|null,
     *   '@region'?: string|null,
     * }|ListTableMetadataInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws MetadataException
     */
    public function listTableMetadata($input): ListTableMetadataOutput
    {
        $input = ListTableMetadataInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTableMetadata', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'MetadataException' => MetadataException::class,
        ]]));

        return new ListTableMetadataOutput($response, $this, $input);
    }

    /**
     * Submits calculations for execution within a session. You can supply the code to run as an inline code block within
     * the request.
     *
     * > The request syntax requires the StartCalculationExecutionRequest$CodeBlock parameter or the
     * > CalculationConfiguration$CodeBlock parameter, but not both. Because CalculationConfiguration$CodeBlock is
     * > deprecated, use the StartCalculationExecutionRequest$CodeBlock parameter instead.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartCalculationExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#startcalculationexecution
     *
     * @param array{
     *   SessionId: string,
     *   Description?: string|null,
     *   CalculationConfiguration?: CalculationConfiguration|array|null,
     *   CodeBlock?: string|null,
     *   ClientRequestToken?: string|null,
     *   '@region'?: string|null,
     * }|StartCalculationExecutionRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function startCalculationExecution($input): StartCalculationExecutionResponse
    {
        $input = StartCalculationExecutionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartCalculationExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new StartCalculationExecutionResponse($response);
    }

    /**
     * Runs the SQL query statements contained in the `Query`. Requires you to have access to the workgroup in which the
     * query ran. Running queries against an external catalog requires GetDataCatalog permission to the catalog. For code
     * samples using the Amazon Web Services SDK for Java, see Examples and Code Samples [^1] in the *Amazon Athena User
     * Guide*.
     *
     * [^1]: http://docs.aws.amazon.com/athena/latest/ug/code-samples.html
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartQueryExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#startqueryexecution
     *
     * @param array{
     *   QueryString: string,
     *   ClientRequestToken?: string|null,
     *   QueryExecutionContext?: QueryExecutionContext|array|null,
     *   ResultConfiguration?: ResultConfiguration|array|null,
     *   WorkGroup?: string|null,
     *   ExecutionParameters?: string[]|null,
     *   ResultReuseConfiguration?: ResultReuseConfiguration|array|null,
     *   EngineConfiguration?: EngineConfiguration|array|null,
     *   '@region'?: string|null,
     * }|StartQueryExecutionInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws TooManyRequestsException
     */
    public function startQueryExecution($input): StartQueryExecutionOutput
    {
        $input = StartQueryExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartQueryExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new StartQueryExecutionOutput($response);
    }

    /**
     * Creates a session for running calculations within a workgroup. The session is ready when it reaches an `IDLE` state.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_StartSession.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#startsession
     *
     * @param array{
     *   Description?: string|null,
     *   WorkGroup: string,
     *   EngineConfiguration: EngineConfiguration|array,
     *   ExecutionRole?: string|null,
     *   MonitoringConfiguration?: MonitoringConfiguration|array|null,
     *   NotebookVersion?: string|null,
     *   SessionIdleTimeoutInMinutes?: int|null,
     *   ClientRequestToken?: string|null,
     *   Tags?: array<Tag|array>|null,
     *   CopyWorkGroupTags?: bool|null,
     *   '@region'?: string|null,
     * }|StartSessionRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     * @throws SessionAlreadyExistsException
     * @throws TooManyRequestsException
     */
    public function startSession($input): StartSessionResponse
    {
        $input = StartSessionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartSession', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
            'SessionAlreadyExistsException' => SessionAlreadyExistsException::class,
            'TooManyRequestsException' => TooManyRequestsException::class,
        ]]));

        return new StartSessionResponse($response);
    }

    /**
     * Requests the cancellation of a calculation. A `StopCalculationExecution` call on a calculation that is already in a
     * terminal state (for example, `STOPPED`, `FAILED`, or `COMPLETED`) succeeds but has no effect.
     *
     * > Cancelling a calculation is done on a best effort basis. If a calculation cannot be cancelled, you can be charged
     * > for its completion. If you are concerned about being charged for a calculation that cannot be cancelled, consider
     * > terminating the session in which the calculation is running.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_StopCalculationExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#stopcalculationexecution
     *
     * @param array{
     *   CalculationExecutionId: string,
     *   '@region'?: string|null,
     * }|StopCalculationExecutionRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function stopCalculationExecution($input): StopCalculationExecutionResponse
    {
        $input = StopCalculationExecutionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopCalculationExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new StopCalculationExecutionResponse($response);
    }

    /**
     * Stops a query execution. Requires you to have access to the workgroup in which the query ran.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_StopQueryExecution.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#stopqueryexecution
     *
     * @param array{
     *   QueryExecutionId: string,
     *   '@region'?: string|null,
     * }|StopQueryExecutionInput $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     */
    public function stopQueryExecution($input): StopQueryExecutionOutput
    {
        $input = StopQueryExecutionInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StopQueryExecution', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
        ]]));

        return new StopQueryExecutionOutput($response);
    }

    /**
     * Terminates an active session. A `TerminateSession` call on a session that is already inactive (for example, in a
     * `FAILED`, `TERMINATED` or `TERMINATING` state) succeeds but has no effect. Calculations running in the session when
     * `TerminateSession` is called are forcefully stopped, but may display as `FAILED` instead of `STOPPED`.
     *
     * @see https://docs.aws.amazon.com/athena/latest/APIReference/API_TerminateSession.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-athena-2017-05-18.html#terminatesession
     *
     * @param array{
     *   SessionId: string,
     *   '@region'?: string|null,
     * }|TerminateSessionRequest $input
     *
     * @throws InternalServerException
     * @throws InvalidRequestException
     * @throws ResourceNotFoundException
     */
    public function terminateSession($input): TerminateSessionResponse
    {
        $input = TerminateSessionRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'TerminateSession', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServerException' => InternalServerException::class,
            'InvalidRequestException' => InvalidRequestException::class,
            'ResourceNotFoundException' => ResourceNotFoundException::class,
        ]]));

        return new TerminateSessionResponse($response);
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

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://athena.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://athena.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-central-1':
                return [
                    'endpoint' => 'https://athena-fips.ca-central-1.amazonaws.com',
                    'signRegion' => 'ca-central-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://athena-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://athena-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://athena-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://athena-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://athena-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://athena-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://athena-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://athena.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://athena.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://athena.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://athena.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'athena',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://athena.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'athena',
            'signVersions' => ['v4'],
        ];
    }
}
