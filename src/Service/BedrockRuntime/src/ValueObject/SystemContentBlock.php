<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * A system content block.
 */
final class SystemContentBlock
{
    /**
     * A system prompt for the model.
     *
     * @var string|null
     */
    private $text;

    /**
     * A content block to assess with the guardrail. Use with the Converse [^1] or ConverseStream [^2] API operations.
     *
     * For more information, see *Use a guardrail with the Converse API* in the *Amazon Bedrock User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
     * [^2]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_ConverseStream.html
     *
     * @var GuardrailConverseContentBlock|null
     */
    private $guardContent;

    /**
     * @param array{
     *   text?: null|string,
     *   guardContent?: null|GuardrailConverseContentBlock|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->text = $input['text'] ?? null;
        $this->guardContent = isset($input['guardContent']) ? GuardrailConverseContentBlock::create($input['guardContent']) : null;
    }

    /**
     * @param array{
     *   text?: null|string,
     *   guardContent?: null|GuardrailConverseContentBlock|array,
     * }|SystemContentBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGuardContent(): ?GuardrailConverseContentBlock
    {
        return $this->guardContent;
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
        if (null !== $v = $this->guardContent) {
            $payload['guardContent'] = $v->requestBody();
        }

        return $payload;
    }
}
