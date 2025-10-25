<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A tool use content block. Contains information about a tool that the model is requesting be run., The model uses the
 * result from the tool to generate a response.
 */
final class ToolUseBlock
{
    /**
     * The ID for the tool request.
     *
     * @var string
     */
    private $toolUseId;

    /**
     * The name of the tool that the model wants to use.
     *
     * @var string
     */
    private $name;

    /**
     * The input to pass to the tool.
     *
     * @var Document
     */
    private $input;

    /**
     * @param array{
     *   toolUseId: string,
     *   name: string,
     *   input: Document|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->toolUseId = $input['toolUseId'] ?? $this->throwException(new InvalidArgument('Missing required field "toolUseId".'));
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->input = isset($input['input']) ? Document::create($input['input']) : $this->throwException(new InvalidArgument('Missing required field "input".'));
    }

    /**
     * @param array{
     *   toolUseId: string,
     *   name: string,
     *   input: Document|array,
     * }|ToolUseBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInput(): Document
    {
        return $this->input;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getToolUseId(): string
    {
        return $this->toolUseId;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->toolUseId;
        $payload['toolUseId'] = $v;
        $v = $this->name;
        $payload['name'] = $v;
        $v = $this->input;
        $payload['input'] = $v->requestBody();

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
