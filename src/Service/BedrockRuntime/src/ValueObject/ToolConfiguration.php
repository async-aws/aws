<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration information for the tools that you pass to a model. For more information, see Tool use (function
 * calling) [^1] in the Amazon Bedrock User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/tool-use.html
 */
final class ToolConfiguration
{
    /**
     * An array of tools that you want to pass to a model.
     *
     * @var Tool[]
     */
    private $tools;

    /**
     * If supported by model, forces the model to request a tool.
     *
     * @var ToolChoice|null
     */
    private $toolChoice;

    /**
     * @param array{
     *   tools: array<Tool|array>,
     *   toolChoice?: null|ToolChoice|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tools = isset($input['tools']) ? array_map([Tool::class, 'create'], $input['tools']) : $this->throwException(new InvalidArgument('Missing required field "tools".'));
        $this->toolChoice = isset($input['toolChoice']) ? ToolChoice::create($input['toolChoice']) : null;
    }

    /**
     * @param array{
     *   tools: array<Tool|array>,
     *   toolChoice?: null|ToolChoice|array,
     * }|ToolConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getToolChoice(): ?ToolChoice
    {
        return $this->toolChoice;
    }

    /**
     * @return Tool[]
     */
    public function getTools(): array
    {
        return $this->tools;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->tools;

        $index = -1;
        $payload['tools'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['tools'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->toolChoice) {
            $payload['toolChoice'] = $v->requestBody();
        }

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
