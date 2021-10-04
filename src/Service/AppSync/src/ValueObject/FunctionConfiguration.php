<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The `Function` object.
 */
final class FunctionConfiguration
{
    /**
     * A unique ID representing the `Function` object.
     */
    private $functionId;

    /**
     * The ARN of the `Function` object.
     */
    private $functionArn;

    /**
     * The name of the `Function` object.
     */
    private $name;

    /**
     * The `Function` description.
     */
    private $description;

    /**
     * The name of the `DataSource`.
     */
    private $dataSourceName;

    /**
     * The `Function` request mapping template. Functions support only the 2018-05-29 version of the request mapping
     * template.
     */
    private $requestMappingTemplate;

    /**
     * The `Function` response mapping template.
     */
    private $responseMappingTemplate;

    /**
     * The version of the request mapping template. Currently only the 2018-05-29 version of the template is supported.
     */
    private $functionVersion;

    private $syncConfig;

    /**
     * @param array{
     *   functionId?: null|string,
     *   functionArn?: null|string,
     *   name?: null|string,
     *   description?: null|string,
     *   dataSourceName?: null|string,
     *   requestMappingTemplate?: null|string,
     *   responseMappingTemplate?: null|string,
     *   functionVersion?: null|string,
     *   syncConfig?: null|SyncConfig|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->functionId = $input['functionId'] ?? null;
        $this->functionArn = $input['functionArn'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->dataSourceName = $input['dataSourceName'] ?? null;
        $this->requestMappingTemplate = $input['requestMappingTemplate'] ?? null;
        $this->responseMappingTemplate = $input['responseMappingTemplate'] ?? null;
        $this->functionVersion = $input['functionVersion'] ?? null;
        $this->syncConfig = isset($input['syncConfig']) ? SyncConfig::create($input['syncConfig']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDataSourceName(): ?string
    {
        return $this->dataSourceName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getFunctionArn(): ?string
    {
        return $this->functionArn;
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
}
