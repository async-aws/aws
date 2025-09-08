<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Enum\ResolverLevelMetricsConfig;
use AsyncAws\AppSync\ValueObject\AppSyncRuntime;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateResolverRequest extends Input
{
    /**
     * The ID for the GraphQL API for which the resolver is being created.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * The name of the `Type`.
     *
     * @required
     *
     * @var string|null
     */
    private $typeName;

    /**
     * The name of the field to attach the resolver to.
     *
     * @required
     *
     * @var string|null
     */
    private $fieldName;

    /**
     * The name of the data source for which the resolver is being created.
     *
     * @var string|null
     */
    private $dataSourceName;

    /**
     * The mapping template to use for requests.
     *
     * A resolver uses a request mapping template to convert a GraphQL expression into a format that a data source can
     * understand. Mapping templates are written in Apache Velocity Template Language (VTL).
     *
     * VTL request mapping templates are optional when using an Lambda data source. For all other data sources, VTL request
     * and response mapping templates are required.
     *
     * @var string|null
     */
    private $requestMappingTemplate;

    /**
     * The mapping template to use for responses from the data source.
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
     *   apiId?: string,
     *   typeName?: string,
     *   fieldName?: string,
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
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->apiId = $input['apiId'] ?? null;
        $this->typeName = $input['typeName'] ?? null;
        $this->fieldName = $input['fieldName'] ?? null;
        $this->dataSourceName = $input['dataSourceName'] ?? null;
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
        parent::__construct($input);
    }

    /**
     * @param array{
     *   apiId?: string,
     *   typeName?: string,
     *   fieldName?: string,
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
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->apiId) {
            throw new InvalidArgument(\sprintf('Missing parameter "apiId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['apiId'] = $v;
        if (null === $v = $this->typeName) {
            throw new InvalidArgument(\sprintf('Missing parameter "typeName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['typeName'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/types/' . rawurlencode($uri['typeName']) . '/resolvers';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setApiId(?string $value): self
    {
        $this->apiId = $value;

        return $this;
    }

    public function setCachingConfig(?CachingConfig $value): self
    {
        $this->cachingConfig = $value;

        return $this;
    }

    public function setCode(?string $value): self
    {
        $this->code = $value;

        return $this;
    }

    public function setDataSourceName(?string $value): self
    {
        $this->dataSourceName = $value;

        return $this;
    }

    public function setFieldName(?string $value): self
    {
        $this->fieldName = $value;

        return $this;
    }

    /**
     * @param ResolverKind::*|null $value
     */
    public function setKind(?string $value): self
    {
        $this->kind = $value;

        return $this;
    }

    public function setMaxBatchSize(?int $value): self
    {
        $this->maxBatchSize = $value;

        return $this;
    }

    /**
     * @param ResolverLevelMetricsConfig::*|null $value
     */
    public function setMetricsConfig(?string $value): self
    {
        $this->metricsConfig = $value;

        return $this;
    }

    public function setPipelineConfig(?PipelineConfig $value): self
    {
        $this->pipelineConfig = $value;

        return $this;
    }

    public function setRequestMappingTemplate(?string $value): self
    {
        $this->requestMappingTemplate = $value;

        return $this;
    }

    public function setResponseMappingTemplate(?string $value): self
    {
        $this->responseMappingTemplate = $value;

        return $this;
    }

    public function setRuntime(?AppSyncRuntime $value): self
    {
        $this->runtime = $value;

        return $this;
    }

    public function setSyncConfig(?SyncConfig $value): self
    {
        $this->syncConfig = $value;

        return $this;
    }

    public function setTypeName(?string $value): self
    {
        $this->typeName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->fieldName) {
            throw new InvalidArgument(\sprintf('Missing parameter "fieldName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['fieldName'] = $v;
        if (null !== $v = $this->dataSourceName) {
            $payload['dataSourceName'] = $v;
        }
        if (null !== $v = $this->requestMappingTemplate) {
            $payload['requestMappingTemplate'] = $v;
        }
        if (null !== $v = $this->responseMappingTemplate) {
            $payload['responseMappingTemplate'] = $v;
        }
        if (null !== $v = $this->kind) {
            if (!ResolverKind::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "kind" for "%s". The value "%s" is not a valid "ResolverKind".', __CLASS__, $v));
            }
            $payload['kind'] = $v;
        }
        if (null !== $v = $this->pipelineConfig) {
            $payload['pipelineConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->syncConfig) {
            $payload['syncConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->cachingConfig) {
            $payload['cachingConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->maxBatchSize) {
            $payload['maxBatchSize'] = $v;
        }
        if (null !== $v = $this->runtime) {
            $payload['runtime'] = $v->requestBody();
        }
        if (null !== $v = $this->code) {
            $payload['code'] = $v;
        }
        if (null !== $v = $this->metricsConfig) {
            if (!ResolverLevelMetricsConfig::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "metricsConfig" for "%s". The value "%s" is not a valid "ResolverLevelMetricsConfig".', __CLASS__, $v));
            }
            $payload['metricsConfig'] = $v;
        }

        return $payload;
    }
}
