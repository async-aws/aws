<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The model must request a specific tool. For example, `{"tool" : {"name" : "Your tool name"}}`.
 *
 * > This field is only supported by Anthropic Claude 3 models.
 */
final class SpecificToolChoice
{
    /**
     * The name of the tool that the model must request.
     *
     * @var string
     */
    private $name;

    /**
     * @param array{
     *   name: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
    }

    /**
     * @param array{
     *   name: string,
     * }|SpecificToolChoice $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
