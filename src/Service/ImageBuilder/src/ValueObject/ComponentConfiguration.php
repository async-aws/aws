<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration details of the component.
 */
final class ComponentConfiguration
{
    /**
     * The Amazon Resource Name (ARN) of the component.
     *
     * @var string
     */
    private $componentArn;

    /**
     * A group of parameter settings that Image Builder uses to configure the component for a specific recipe.
     *
     * @var ComponentParameter[]|null
     */
    private $parameters;

    /**
     * @param array{
     *   componentArn: string,
     *   parameters?: array<ComponentParameter|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->componentArn = $input['componentArn'] ?? $this->throwException(new InvalidArgument('Missing required field "componentArn".'));
        $this->parameters = isset($input['parameters']) ? array_map([ComponentParameter::class, 'create'], $input['parameters']) : null;
    }

    /**
     * @param array{
     *   componentArn: string,
     *   parameters?: array<ComponentParameter|array>|null,
     * }|ComponentConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getComponentArn(): string
    {
        return $this->componentArn;
    }

    /**
     * @return ComponentParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
