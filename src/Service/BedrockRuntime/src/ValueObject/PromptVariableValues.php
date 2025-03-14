<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * Contains a map of variables in a prompt from Prompt management to an object containing the values to fill in for them
 * when running model invocation. For more information, see How Prompt management works [^1].
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/userguide/prompt-management-how.html
 */
final class PromptVariableValues
{
    /**
     * The text value that the variable maps to.
     *
     * @var string|null
     */
    private $text;

    /**
     * @param array{
     *   text?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->text = $input['text'] ?? null;
    }

    /**
     * @param array{
     *   text?: null|string,
     * }|PromptVariableValues $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->text) {
            $payload['text'] = $v;
        }

        return $payload;
    }
}
