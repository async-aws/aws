<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class Operation
{
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

    public static function create(array $data, ServiceDefinition $service, ?Pagination $pagination, Example $example, \Closure $shapeLocator): self
    {
        $operation = new self();
        $operation->data = $data;
        $operation->service = $service;
        $operation->pagination = $pagination;
        $operation->example = $example;
        $operation->shapeLocator = $shapeLocator;

        return $operation;
    }

    public function getName(): string
    {
        return $this->data['name'];
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

    public function getDocumentationUrl(): ?string
    {
        return $this->data['documentationUrl'] ?? null;
    }

    public function getHttpRequestUri(): ?string
    {
        return $this->data['http']['requestUri'] ?? null;
    }

    public function getHttpMethod(): ?string
    {
        return $this->data['http']['method'] ?? null;
    }
}
