<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\AppSync\Enum\ResolverKind;

/**
 * The `Resolver` object.
 */
final class Resolver
{
    /**
     * The resolver type name.
     */
    private $typeName;

    /**
     * The resolver field name.
     */
    private $fieldName;

    /**
     * The resolver data source name.
     */
    private $dataSourceName;

    /**
     * The resolver Amazon Resource Name (ARN).
     */
    private $resolverArn;

    /**
     * The request mapping template.
     */
    private $requestMappingTemplate;

    /**
     * The response mapping template.
     */
    private $responseMappingTemplate;

    /**
     * The resolver type.
     */
    private $kind;

    /**
     * The `PipelineConfig`.
     */
    private $pipelineConfig;

    /**
     * The `SyncConfig` for a resolver attached to a versioned data source.
     */
    private $syncConfig;

    /**
     * The caching configuration for the resolver.
     */
    private $cachingConfig;

    /**
     * The maximum batching size for a resolver.
     */
    private $maxBatchSize;

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
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCachingConfig(): ?CachingConfig
    {
        return $this->cachingConfig;
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

    public function getSyncConfig(): ?SyncConfig
    {
        return $this->syncConfig;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }
}
