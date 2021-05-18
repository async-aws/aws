<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Operation
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $data;

    /**
     * @var ServiceDefinition
     */
    private $service;

    /**
     * @var ?Pagination
     */
    private $pagination;

    /**
     * @var Example
     */
    private $example;

    /**
     * @var \Closure
     */
    private $shapeLocator;

    private function __construct()
    {
    }

    public static function create(string $name, array $data, ServiceDefinition $service, ?Pagination $pagination, Example $example, \Closure $shapeLocator): self
    {
        $operation = new self();
        $operation->name = $name;
        $operation->data = $data;
        $operation->service = $service;
        $operation->pagination = $pagination;
        $operation->example = $example;
        $operation->shapeLocator = $shapeLocator;

        return $operation;
    }

    /**
     * This is the operation name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getMethodName(): string
    {
        return $this->data['_method_name'];
    }

    public function getService(): ServiceDefinition
    {
        return $this->service;
    }

    public function getDocumentation(): ?string
    {
        return $this->data['_documentation'] ?? null;
    }

    public function getApiVersion(): string
    {
        return $this->data['_apiVersion'];
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }

    public function getExample(): Example
    {
        return $this->example;
    }

    public function getOutput(): ?StructureShape
    {
        if (isset($this->data['output']['shape'])) {
            return ($this->shapeLocator)($this->data['output']['shape'], null, ['resultWrapper' => $this->data['output']['resultWrapper'] ?? null]);
        }

        return null;
    }

    /**
     * @return ExceptionShape[]
     */
    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->data['errors'] ?? [] as $error) {
            if (isset($errors[$error['shape']])) {
                continue;
            }
            /** @var ExceptionShape $shape */
            $shape = ($this->shapeLocator)($error['shape']);
            $errors[$error['shape']] = $shape;
        }

        return array_values($errors);
    }

    public function getInput(): StructureShape
    {
        if (isset($this->data['input']['shape'])) {
            $shape = ($this->shapeLocator)($this->data['input']['shape']);

            if (!$shape instanceof StructureShape) {
                throw new \InvalidArgumentException(sprintf('The operation "%s" should have an Structure Input.', $this->getName()));
            }

            return $shape;
        }

        throw new \InvalidArgumentException(sprintf('The operation "%s" does not have Input.', $this->getName()));
    }

    public function getInputLocation(): ?string
    {
        return $this->data['input']['locationName'] ?? null;
    }

    public function getInputXmlNamespaceUri(): ?string
    {
        return $this->data['input']['xmlNamespace']['uri'] ?? null;
    }

    public function getUserGuideDocumentationUrl(): ?string
    {
        return $this->data['documentationUrl'] ?? null;
    }

    public function getApiReferenceDocumentationUrl(): string
    {
        return $this->service->getApiReferenceUrl() . '/API_' . $this->data['name'] . '.html';
    }

    public function getHttpRequestUri(): ?string
    {
        return $this->data['http']['requestUri'] ?? null;
    }

    public function getHttpMethod(): string
    {
        if (isset($this->data['input']['method'])) {
            throw new \InvalidArgumentException(sprintf('The operation "%s" should have an HTTP Method.', $this->getName()));
        }

        return $this->data['http']['method'];
    }

    public function hasBody(): bool
    {
        return \in_array($this->getHttpMethod(), ['PUT', 'POST']);
    }

    public function isDeprecated(): bool
    {
        return $this->data['deprecated'] ?? false;
    }
}
