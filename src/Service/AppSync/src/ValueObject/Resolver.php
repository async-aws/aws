<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Enum\ResolverLevelMetricsConfig;

/**
 * Describes a resolver.
 */
final class Resolver
{
    /**
     * The resolver type name.
     *
     * @var string|null
     */
    private $typeName;

    /**
     * The resolver field name.
     *
     * @var string|null
     */
    private $fieldName;

    /**
     * The resolver data source name.
     *
     * @var string|null
     */
    private $dataSourceName;

    /**
     * The resolver Amazon Resource Name (ARN).
     *
     * @var string|null
     */
    private $resolverArn;

    /**
     * The request mapping template.
     *
     * @var string|null
     */
    private $requestMappingTemplate;

    /**
     * The response mapping template.
     *
     * @var string|null
     */
    private $responseMappingTemplate;

    /**
     * The resolver type.
     *
     * - **UNIT**: A UNIT resolver type. A UNIT resolver is the default resolver type. You can use a UNIT resolver to run a
     *   GraphQL query against a single data source.
     * - **PIPELINE**: A PIPELINE resolver type. You can use a PIPELINE resolver to invoke a series of `Function` objects in
     *   a serial manner. You can use a pipeline resolver to run a GraphQL query against multiple data sources.
     *
     * @var ResolverKind::*|null
     */
    private $kind;

    /**
     * The `PipelineConfig`.
     *
     * @var PipelineConfig|null
     */
    private $pipelineConfig;

    /**
     * The `SyncConfig` for a resolver attached to a versioned data source.
     *
     * @var SyncConfig|null
     */
    private $syncConfig;

    /**
     * The caching configuration for the resolver.
     *
     * @var CachingConfig|null
     */
    private $cachingConfig;

    /**
     * The maximum batching size for a resolver.
     *
     * @var int|null
     */
    private $maxBatchSize;

    /**
     * @var AppSyncRuntime|null
     */
    private $runtime;

    /**
     * The `resolver` code that contains the request and response functions. When code is used, the `runtime` is required.
     * The `runtime` value must be `APPSYNC_JS`.
     *
     * @var string|null
     */
    private $code;

    /**
     * Enables or disables enhanced resolver metrics for specified resolvers. Note that `metricsConfig` won't be used unless
     * the `resolverLevelMetricsBehavior` value is set to `PER_RESOLVER_METRICS`. If the `resolverLevelMetricsBehavior` is
     * set to `FULL_REQUEST_RESOLVER_METRICS` instead, `metricsConfig` will be ignored. However, you can still set its
     * value.
     *
     * `metricsConfig` can be `ENABLED` or `DISABLED`.
     *
     * @var ResolverLevelMetricsConfig::*|null
     */
    private $metricsConfig;

    /**
     * @param array{
     *   typeName?: string|null,
     *   fieldName?: string|null,
     *   dataSourceName?: string|null,
     *   resolverArn?: string|null,
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
     * } $input
     */
    public function __construct(array $input)
    {
        $this->typeName = $input['typeName'] ?? null;
        $this->fieldName = $input['fieldName'] ?? null;
        $this->dataSourceName = $input['dataSourceName'] ?? null;
        $this->resolverArn = $input['resolverArn'] ?? null;
        $this->requestMappingTemplate = $input['requestMappingTemplate'] ?? null;
        $this->responseMappingTemplate = $input['responseMappingTemplate'] ?? null;
        $this->kind = $input['kind'] ?? null;
        $this->pipelineConfig = isset($input['pipelineConfig']) ? PipelineConfig::create($input['pipelineConfig']) : null;
        $this->syncConfig = isset($input['syncConfig']) ? SyncConfig::create($input['syncConfig']) : null;
        $this->cachingConfig = isset($input['cachingConfig']) ? CachingConfig::create($input['cachingConfig']) : null;
        $this->maxBatchSize = $input['maxBatchSize'] ?? null;
        $this->runtime = isset($input['runtime']) ? AppSyncRuntime::create($input['runtime']) : null;
        $this->code = $input['code'] ?? null;
        $this->metricsConfig = $input['metricsConfig'] ?? null;
    }

    /**
     * @param array{
     *   typeName?: string|null,
     *   fieldName?: string|null,
     *   dataSourceName?: string|null,
     *   resolverArn?: string|null,
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
     * }|Resolver $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCachingConfig(): ?CachingConfig
    {
        return $this->cachingConfig;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getDataSourceName(): ?string
    {
        return $this->dataSourceName;
    }

    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    /**
     * @return ResolverKind::*|null
     */
    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function getMaxBatchSize(): ?int
    {
        return $this->maxBatchSize;
    }

    /**
     * @return ResolverLevelMetricsConfig::*|null
     */
    public function getMetricsConfig(): ?string
    {
        return $this->metricsConfig;
    }

    public function getPipelineConfig(): ?PipelineConfig
    {
        return $this->pipelineConfig;
    }

    public function getRequestMappingTemplate(): ?string
    {
        return $this->requestMappingTemplate;
    }

    public function getResolverArn(): ?string
    {
        return $this->resolverArn;
    }

    public function getResponseMappingTemplate(): ?string
    {
        return $this->responseMappingTemplate;
    }

    public function getRuntime(): ?AppSyncRuntime
    {
        return $this->runtime;
    }

    public function getSyncConfig(): ?SyncConfig
    {
        return $this->syncConfig;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }
}
