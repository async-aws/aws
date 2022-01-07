<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class UpdateResolverRequest extends Input
{
    /**
     * The API ID.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * The new type name.
     *
     * @required
     *
     * @var string|null
     */
    private $typeName;

    /**
     * The new field name.
     *
     * @required
     *
     * @var string|null
     */
    private $fieldName;

    /**
     * The new data source name.
     *
     * @var string|null
     */
    private $dataSourceName;

    /**
     * The new request mapping template.
     *
     * @var string|null
     */
    private $requestMappingTemplate;

    /**
     * The new response mapping template.
     *
     * @var string|null
     */
    private $responseMappingTemplate;

    /**
     * The resolver type.
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
     * @param array{
     *   apiId?: string,
     *   typeName?: string,
     *   fieldName?: string,
     *   dataSourceName?: string,
     *   requestMappingTemplate?: string,
     *   responseMappingTemplate?: string,
     *   kind?: ResolverKind::*,
     *   pipelineConfig?: PipelineConfig|array,
     *   syncConfig?: SyncConfig|array,
     *   cachingConfig?: CachingConfig|array,
     *   maxBatchSize?: int,
     *   @region?: string,
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
        parent::__construct($input);
    }

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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->apiId) {
            throw new InvalidArgument(sprintf('Missing parameter "apiId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['apiId'] = $v;
        if (null === $v = $this->typeName) {
            throw new InvalidArgument(sprintf('Missing parameter "typeName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['typeName'] = $v;
        if (null === $v = $this->fieldName) {
            throw new InvalidArgument(sprintf('Missing parameter "fieldName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['fieldName'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/types/' . rawurlencode($uri['typeName']) . '/resolvers/' . rawurlencode($uri['fieldName']);

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
                throw new InvalidArgument(sprintf('Invalid parameter "kind" for "%s". The value "%s" is not a valid "ResolverKind".', __CLASS__, $v));
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

        return $payload;
    }
}
