<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * Information about a tool that you can use with the Converse API. For more information, see Tool use (function
 * calling) [^1] in the Amazon Bedrock User Guide.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/tool-use.html
 */
final class Tool
{
    /**
     * The specfication for the tool.
     *
     * @var ToolSpecification|null
     */
    private $toolSpec;

    /**
     * @param array{
     *   toolSpec?: null|ToolSpecification|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->toolSpec = isset($input['toolSpec']) ? ToolSpecification::create($input['toolSpec']) : null;
    }

    /**
     * @param array{
     *   toolSpec?: null|ToolSpecification|array,
     * }|Tool $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getToolSpec(): ?ToolSpecification
    {
        return $this->toolSpec;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->toolSpec) {
            $payload['toolSpec'] = $v->requestBody();
        }

        return $payload;
    }
}
