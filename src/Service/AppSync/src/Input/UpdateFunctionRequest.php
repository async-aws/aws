<?php

namespace AsyncAws\AppSync\Input;

use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class UpdateFunctionRequest extends Input
{
    /**
     * The GraphQL API ID.
     *
     * @required
     *
     * @var string|null
     */
    private $apiId;

    /**
     * The `Function` name.
     *
     * @required
     *
     * @var string|null
     */
    private $name;

    /**
     * The `Function` description.
     *
     * @var string|null
     */
    private $description;

    /**
     * The function ID.
     *
     * @required
     *
     * @var string|null
     */
    private $functionId;

    /**
     * The `Function``DataSource` name.
     *
     * @required
     *
     * @var string|null
     */
    private $dataSourceName;

    /**
     * The `Function` request mapping template. Functions support only the 2018-05-29 version of the request mapping
     * template.
     *
     * @var string|null
     */
    private $requestMappingTemplate;

    /**
     * The `Function` request mapping template.
     *
     * @var string|null
     */
    private $responseMappingTemplate;

    /**
     * The `version` of the request mapping template. Currently the supported value is 2018-05-29.
     *
     * @required
     *
     * @var string|null
     */
    private $functionVersion;

    /**
     * @var SyncConfig|null
     */
    private $syncConfig;

    /**
     * @param array{
     *   apiId?: string,
     *   name?: string,
     *   description?: string,
     *   functionId?: string,
     *   dataSourceName?: string,
     *   requestMappingTemplate?: string,
     *   responseMappingTemplate?: string,
     *   functionVersion?: string,
     *   syncConfig?: SyncConfig|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->apiId = $input['apiId'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->functionId = $input['functionId'] ?? null;
        $this->dataSourceName = $input['dataSourceName'] ?? null;
        $this->requestMappingTemplate = $input['requestMappingTemplate'] ?? null;
        $this->responseMappingTemplate = $input['responseMappingTemplate'] ?? null;
        $this->functionVersion = $input['functionVersion'] ?? null;
        $this->syncConfig = isset($input['syncConfig']) ? SyncConfig::create($input['syncConfig']) : null;
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

    public function getDataSourceName(): ?string
    {
        return $this->dataSourceName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getFunctionId(): ?string
    {
        return $this->functionId;
    }

    public function getFunctionVersion(): ?string
    {
        return $this->functionVersion;
    }

    public function getName(): ?string
    {
        return $this->name;
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
        if (null === $v = $this->functionId) {
            throw new InvalidArgument(sprintf('Missing parameter "functionId" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['functionId'] = $v;
        $uriString = '/v1/apis/' . rawurlencode($uri['apiId']) . '/functions/' . rawurlencode($uri['functionId']);

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

    public function setDataSourceName(?string $value): self
    {
        $this->dataSourceName = $value;

        return $this;
    }

    public function setDescription(?string $value): self
    {
        $this->description = $value;

        return $this;
    }

    public function setFunctionId(?string $value): self
    {
        $this->functionId = $value;

        return $this;
    }

    public function setFunctionVersion(?string $value): self
    {
        $this->functionVersion = $value;

        return $this;
    }

    public function setName(?string $value): self
    {
        $this->name = $value;

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

    private function requestBody(): array
    {
        $payload = [];

        if (null === $v = $this->name) {
            throw new InvalidArgument(sprintf('Missing parameter "name" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['name'] = $v;
        if (null !== $v = $this->description) {
            $payload['description'] = $v;
        }

        if (null === $v = $this->dataSourceName) {
            throw new InvalidArgument(sprintf('Missing parameter "dataSourceName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['dataSourceName'] = $v;
        if (null !== $v = $this->requestMappingTemplate) {
            $payload['requestMappingTemplate'] = $v;
        }
        if (null !== $v = $this->responseMappingTemplate) {
            $payload['responseMappingTemplate'] = $v;
        }
        if (null === $v = $this->functionVersion) {
            throw new InvalidArgument(sprintf('Missing parameter "functionVersion" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['functionVersion'] = $v;
        if (null !== $v = $this->syncConfig) {
            $payload['syncConfig'] = $v->requestBody();
        }

        return $payload;
    }
}
