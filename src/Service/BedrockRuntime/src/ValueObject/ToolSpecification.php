<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The specification for the tool.
 */
final class ToolSpecification
{
    /**
     * The name for the tool.
     *
     * @var string
     */
    private $name;

    /**
     * The description for the tool.
     *
     * @var string|null
     */
    private $description;

    /**
     * The input schema for the tool in JSON format.
     *
     * @var ToolInputSchema
     */
    private $inputSchema;

    /**
     * @param array{
     *   name: string,
     *   description?: null|string,
     *   inputSchema: ToolInputSchema|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->description = $input['description'] ?? null;
        $this->inputSchema = isset($input['inputSchema']) ? ToolInputSchema::create($input['inputSchema']) : $this->throwException(new InvalidArgument('Missing required field "inputSchema".'));
    }

    /**
     * @param array{
     *   name: string,
     *   description?: null|string,
     *   inputSchema: ToolInputSchema|array,
     * }|ToolSpecification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getInputSchema(): ToolInputSchema
    {
        return $this->inputSchema;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->name;
        $payload['name'] = $v;
        if (null !== $v = $this->description) {
            $payload['description'] = $v;
        }
        $v = $this->inputSchema;
        $payload['inputSchema'] = $v->requestBody();

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
