<?php

namespace AsyncAws\AppSync;

use AsyncAws\AppSync\Enum\DataSourceType;
use AsyncAws\AppSync\Enum\ResolverKind;
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
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\DynamodbDataSourceConfig;
use AsyncAws\AppSync\ValueObject\ElasticsearchDataSourceConfig;
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
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_CreateResolver.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#createresolver
     *
     * @param array{
     *   apiId: string,
     *   typeName: string,
     *   fieldName: string,
     *   dataSourceName?: string,
     *   requestMappingTemplate?: string,
     *   responseMappingTemplate?: string,
     *   kind?: ResolverKind::*,
     *   pipelineConfig?: PipelineConfig|array,
     *   syncConfig?: SyncConfig|array,
     *   cachingConfig?: CachingConfig|array,
     *   @region?: string,
     * }|CreateResolverRequest $input
     *
     * @throws ConcurrentModificationException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function createResolver($input): CreateResolverResponse
    {
        $input = CreateResolverRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateResolver', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
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
     *   @region?: string,
     * }|DeleteResolverRequest $input
     *
     * @throws ConcurrentModificationException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function deleteResolver($input): DeleteResolverResponse
    {
        $input = DeleteResolverRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteResolver', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
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
     *   @region?: string,
     * }|GetSchemaCreationStatusRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function getSchemaCreationStatus($input): GetSchemaCreationStatusResponse
    {
        $input = GetSchemaCreationStatusRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetSchemaCreationStatus', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new GetSchemaCreationStatusResponse($response);
    }

    /**
     * Lists the API keys for a given API.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListApiKeys.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#listapikeys
     *
     * @param array{
     *   apiId: string,
     *   nextToken?: string,
     *   maxResults?: int,
     *   @region?: string,
     * }|ListApiKeysRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function listApiKeys($input): ListApiKeysResponse
    {
        $input = ListApiKeysRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListApiKeys', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new ListApiKeysResponse($response);
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
     *   nextToken?: string,
     *   maxResults?: int,
     *   @region?: string,
     * }|ListResolversRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function listResolvers($input): ListResolversResponse
    {
        $input = ListResolversRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListResolvers', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new ListResolversResponse($response);
    }

    /**
     * Adds a new schema to your GraphQL API.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_StartSchemaCreation.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#startschemacreation
     *
     * @param array{
     *   apiId: string,
     *   definition: string,
     *   @region?: string,
     * }|StartSchemaCreationRequest $input
     *
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function startSchemaCreation($input): StartSchemaCreationResponse
    {
        $input = StartSchemaCreationRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'StartSchemaCreation', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
        ]]));

        return new StartSchemaCreationResponse($response);
    }

    /**
     * Updates an API key. The key can be updated while it is not deleted.
     *
     * @see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateApiKey.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-appsync-2017-07-25.html#updateapikey
     *
     * @param array{
     *   apiId: string,
     *   id: string,
     *   description?: string,
     *   expires?: string,
     *   @region?: string,
     * }|UpdateApiKeyRequest $input
     *
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws LimitExceededException
     * @throws InternalFailureException
     * @throws ApiKeyValidityOutOfBoundsException
     */
    public function updateApiKey($input): UpdateApiKeyResponse
    {
        $input = UpdateApiKeyRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateApiKey', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'LimitExceededException' => LimitExceededException::class,
            'InternalFailureException' => InternalFailureException::class,
            'ApiKeyValidityOutOfBoundsException' => ApiKeyValidityOutOfBoundsException::class,
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
     *   description?: string,
     *   type: DataSourceType::*,
     *   serviceRoleArn?: string,
     *   dynamodbConfig?: DynamodbDataSourceConfig|array,
     *   lambdaConfig?: LambdaDataSourceConfig|array,
     *   elasticsearchConfig?: ElasticsearchDataSourceConfig|array,
     *   openSearchServiceConfig?: OpenSearchServiceDataSourceConfig|array,
     *   httpConfig?: HttpDataSourceConfig|array,
     *   relationalDatabaseConfig?: RelationalDatabaseDataSourceConfig|array,
     *   @region?: string,
     * }|UpdateDataSourceRequest $input
     *
     * @throws BadRequestException
     * @throws ConcurrentModificationException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function updateDataSource($input): UpdateDataSourceResponse
    {
        $input = UpdateDataSourceRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateDataSource', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'BadRequestException' => BadRequestException::class,
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
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
     *   dataSourceName?: string,
     *   requestMappingTemplate?: string,
     *   responseMappingTemplate?: string,
     *   kind?: ResolverKind::*,
     *   pipelineConfig?: PipelineConfig|array,
     *   syncConfig?: SyncConfig|array,
     *   cachingConfig?: CachingConfig|array,
     *   @region?: string,
     * }|UpdateResolverRequest $input
     *
     * @throws ConcurrentModificationException
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws InternalFailureException
     */
    public function updateResolver($input): UpdateResolverResponse
    {
        $input = UpdateResolverRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'UpdateResolver', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ConcurrentModificationException' => ConcurrentModificationException::class,
            'NotFoundException' => NotFoundException::class,
            'UnauthorizedException' => UnauthorizedException::class,
            'InternalFailureException' => InternalFailureException::class,
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
