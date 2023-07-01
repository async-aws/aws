<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\ResolverKind;

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
     * @param array{
     *   typeName?: null|string,
     *   fieldName?: null|string,
     *   dataSourceName?: null|string,
     *   resolverArn?: null|string,
     *   requestMappingTemplate?: null|string,
     *   responseMappingTemplate?: null|string,
     *   kind?: null|ResolverKind::*,
     *   pipelineConfig?: null|PipelineConfig|array,
     *   syncConfig?: null|SyncConfig|array,
     *   cachingConfig?: null|CachingConfig|array,
     *   maxBatchSize?: null|int,
     *   runtime?: null|AppSyncRuntime|array,
     *   code?: null|string,
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
    }

    /**
     * @param array{
     *   typeName?: null|string,
     *   fieldName?: null|string,
     *   dataSourceName?: null|string,
     *   resolverArn?: null|string,
     *   requestMappingTemplate?: null|string,
     *   responseMappingTemplate?: null|string,
     *   kind?: null|ResolverKind::*,
     *   pipelineConfig?: null|PipelineConfig|array,
     *   syncConfig?: null|SyncConfig|array,
     *   cachingConfig?: null|CachingConfig|array,
     *   maxBatchSize?: null|int,
     *   runtime?: null|AppSyncRuntime|array,
     *   code?: null|string,
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
