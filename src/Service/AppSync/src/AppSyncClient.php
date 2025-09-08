<?php

namespace AsyncAws\AppSync;

use AsyncAws\AppSync\Enum\DataSourceLevelMetricsConfig;
use AsyncAws\AppSync\Enum\DataSourceType;
use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Enum\ResolverLevelMetricsConfig;
use AsyncAws\AppSync\Exception\ApiKeyValidityOutOfBoundsException;
use AsyncAws\AppSync\Exception\BadRequestException;
use AsyncAws\AppSync\Exception\ConcurrentModificationException;
use AsyncAws\AppSync\Exception\InternalFailureException;
use AsyncAws\AppSync\Exception\LimitExceededException;
use AsyncAws\AppSync\Exception\NotFoundException;
use AsyncAws\AppSync\Exception\UnauthorizedException;
use AsyncAws\AppSync\Input\CreateResolverRequest;
use AsyncAws\AppSync\Input\DeleteResolverRequest;
use AsyncAws\AppSync\Input\GetSchemaCreationStatusRequest;
use AsyncAws\AppSync\Input\ListApiKeysRequest;
use AsyncAws\AppSync\Input\ListResolversRequest;
use AsyncAws\AppSync\Input\StartSchemaCreationRequest;
use AsyncAws\AppSync\Input\UpdateApiKeyRequest;
use AsyncAws\AppSync\Input\UpdateDataSourceRequest;
use AsyncAws\AppSync\Input\UpdateResolverRequest;
use AsyncAws\AppSync\Result\CreateResolverResponse;
use AsyncAws\AppSync\Result\DeleteResolverResponse;
use AsyncAws\AppSync\Result\GetSchemaCreationStatusResponse;
use AsyncAws\AppSync\Result\ListApiKeysResponse;
use AsyncAws\AppSync\Result\ListResolversResponse;
use AsyncAws\AppSync\Result\StartSchemaCreationResponse;
use AsyncAws\AppSync\Result\UpdateApiKeyResponse;
use AsyncAws\AppSync\Result\UpdateDataSourceResponse;
use AsyncAws\AppSync\Result\UpdateResolverResponse;
use AsyncAws\AppSync\ValueObject\AppSyncRuntime;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\DynamodbDataSourceConfig;
use AsyncAws\AppSync\ValueObject\ElasticsearchDataSourceConfig;
use AsyncAws\AppSync\ValueObject\EventBridgeDataSourceConfig;
use AsyncAws\AppSync\ValueObject\HttpDataSourceConfig;
use AsyncAws\AppSync\ValueObject\LambdaDataSourceConfig;
use AsyncAws\AppSync\ValueObject\OpenSearchServiceDataSourceConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\RelationalDatabaseDataSourceConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class AppSyncClient extends AbstractApi
{
    /**
     * Creates a `Resolver` object.
     *
     * A resolver converts incoming requests into a format that a data source can understand, and converts the data source's
     * responses into GraphQL.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_CreateResolver.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#createresolver
     *
     * @param array{
     *   apiId: string,
     *   typeName: string,
     *   fieldName: string,
     *   dataSourceName?: string|null,
     *   requestMappingTemplate?: string|null,
     *   responseMappingTemplate?: string|null,
     *   kind?: ResolverKind::*|null,
     *   pipelineConfig?: PipelineConfig|array|null,
     *   syncConfig?: SyncConfig|array|null,
     *   cachingConfig?: CachingConfig|array|null,
     *   maxBatchSize?: int|null,
     *   runtime?: AppSyncRuntime|array|null,
     *   code?: string|null,
     *   metricsConfig?: ResolverLevelMetricsConfig::*|null,
     *   '@region'?: string|null,
     * }|CreateResolverRequest $input
     *
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function createResolver($input): CreateResolverResponse
    {
        $input = CreateResolverRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateResolver', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new CreateResolverResponse($response);
    }

    /**
     * Deletes a `Resolver` object.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_DeleteResolver.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#deleteresolver
     *
     * @param array{
     *   apiId: string,
     *   typeName: string,
     *   fieldName: string,
     *   '@region'?: string|null,
     * }|DeleteResolverRequest $input
     *
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function deleteResolver($input): DeleteResolverResponse
    {
        $input = DeleteResolverRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteResolver', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new DeleteResolverResponse($response);
    }

    /**
     * Retrieves the current status of a schema creation operation.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_GetSchemaCreationStatus.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#getschemacreationstatus
     *
     * @param array{
     *   apiId: string,
     *   '@region'?: string|null,
     * }|GetSchemaCreationStatusRequest $input
     *
     * @throws BadRequestException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function getSchemaCreationStatus($input): GetSchemaCreationStatusResponse
    {
        $input = GetSchemaCreationStatusRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSchemaCreationStatus', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new GetSchemaCreationStatusResponse($response);
    }

    /**
     * Lists the API keys for a given API.
     *
     * > API keys are deleted automatically 60 days after they expire. However, they may still be included in the response
     * > until they have actually been deleted. You can safely call `DeleteApiKey` to manually delete a key before it's
     * > automatically deleted.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListApiKeys.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#listapikeys
     *
     * @param array{
     *   apiId: string,
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   '@region'?: string|null,
     * }|ListApiKeysRequest $input
     *
     * @throws BadRequestException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function listApiKeys($input): ListApiKeysResponse
    {
        $input = ListApiKeysRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListApiKeys', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new ListApiKeysResponse($response, $this, $input);
    }

    /**
     * Lists the resolvers for a given API and type.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListResolvers.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#listresolvers
     *
     * @param array{
     *   apiId: string,
     *   typeName: string,
     *   nextToken?: string|null,
     *   maxResults?: int|null,
     *   '@region'?: string|null,
     * }|ListResolversRequest $input
     *
     * @throws BadRequestException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function listResolvers($input): ListResolversResponse
    {
        $input = ListResolversRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListResolvers', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new ListResolversResponse($response, $this, $input);
    }

    /**
     * Adds a new schema to your GraphQL API.
     *
     * This operation is asynchronous. Use to determine when it has completed.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_StartSchemaCreation.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#startschemacreation
     *
     * @param array{
     *   apiId: string,
     *   definition: string,
     *   '@region'?: string|null,
     * }|StartSchemaCreationRequest $input
     *
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function startSchemaCreation($input): StartSchemaCreationResponse
    {
        $input = StartSchemaCreationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartSchemaCreation', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new StartSchemaCreationResponse($response);
    }

    /**
     * Updates an API key. You can update the key as long as it's not deleted.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateApiKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#updateapikey
     *
     * @param array{
     *   apiId: string,
     *   id: string,
     *   description?: string|null,
     *   expires?: int|null,
     *   '@region'?: string|null,
     * }|UpdateApiKeyRequest $input
     *
     * @throws ApiKeyValidityOutOfBoundsException
     * @throws BadRequestException
     * @throws InternalFailureException
     * @throws LimitExceededException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function updateApiKey($input): UpdateApiKeyResponse
    {
        $input = UpdateApiKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateApiKey', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ApiKeyValidityOutOfBoundsException' => ApiKeyValidityOutOfBoundsException::class,
            'BadRequestException' => BadRequestException::class,
            'InternalFailureException' => InternalFailureException::class,
            'LimitExceededException' => LimitExceededException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new UpdateApiKeyResponse($response);
    }

    /**
     * Updates a `DataSource` object.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateDataSource.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#updatedatasource
     *
     * @param array{
     *   apiId: string,
     *   name: string,
     *   description?: string|null,
     *   type: DataSourceType::*,
     *   serviceRoleArn?: string|null,
     *   dynamodbConfig?: DynamodbDataSourceConfig|array|null,
     *   lambdaConfig?: LambdaDataSourceConfig|array|null,
     *   elasticsearchConfig?: ElasticsearchDataSourceConfig|array|null,
     *   openSearchServiceConfig?: OpenSearchServiceDataSourceConfig|array|null,
     *   httpConfig?: HttpDataSourceConfig|array|null,
     *   relationalDatabaseConfig?: RelationalDatabaseDataSourceConfig|array|null,
     *   eventBridgeConfig?: EventBridgeDataSourceConfig|array|null,
     *   metricsConfig?: DataSourceLevelMetricsConfig::*|null,
     *   '@region'?: string|null,
     * }|UpdateDataSourceRequest $input
     *
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function updateDataSource($input): UpdateDataSourceResponse
    {
        $input = UpdateDataSourceRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateDataSource', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new UpdateDataSourceResponse($response);
    }

    /**
     * Updates a `Resolver` object.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateResolver.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#updateresolver
     *
     * @param array{
     *   apiId: string,
     *   typeName: string,
     *   fieldName: string,
     *   dataSourceName?: string|null,
     *   requestMappingTemplate?: string|null,
     *   responseMappingTemplate?: string|null,
     *   kind?: ResolverKind::*|null,
     *   pipelineConfig?: PipelineConfig|array|null,
     *   syncConfig?: SyncConfig|array|null,
     *   cachingConfig?: CachingConfig|array|null,
     *   maxBatchSize?: int|null,
     *   runtime?: AppSyncRuntime|array|null,
     *   code?: string|null,
     *   metricsConfig?: ResolverLevelMetricsConfig::*|null,
     *   '@region'?: string|null,
     * }|UpdateResolverRequest $input
     *
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws InternalFailureException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function updateResolver($input): UpdateResolverResponse
    {
        $input = UpdateResolverRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateResolver', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'InternalFailureException' => InternalFailureException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
        ]]));

        return new UpdateResolverResponse($response);
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
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://appsync.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'appsync',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://appsync.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'appsync',
            'signVersions' => ['v4'],
        ];
    }
}
