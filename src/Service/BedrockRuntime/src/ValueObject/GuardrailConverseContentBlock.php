<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * A content block for selective guarding with the Converse [^1] or ConverseStream [^2] API operations.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 * [^2]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_ConverseStream.html
 */
final class GuardrailConverseContentBlock
{
    /**
     * The text to guard.
     *
     * @var GuardrailConverseTextBlock|null
     */
    private $text;

    /**
     * Image within converse content block to be evaluated by the guardrail.
     *
     * @var GuardrailConverseImageBlock|null
     */
    private $image;

    /**
     * @param array{
     *   text?: null|GuardrailConverseTextBlock|array,
     *   image?: null|GuardrailConverseImageBlock|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->text = isset($input['text']) ? GuardrailConverseTextBlock::create($input['text']) : null;
        $this->image = isset($input['image']) ? GuardrailConverseImageBlock::create($input['image']) : null;
    }

    /**
     * @param array{
     *   text?: null|GuardrailConverseTextBlock|array,
     *   image?: null|GuardrailConverseImageBlock|array,
     * }|GuardrailConverseContentBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getImage(): ?GuardrailConverseImageBlock
    {
        return $this->image;
    }

    public function getText(): ?GuardrailConverseTextBlock
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
            $payload['text'] = $v->requestBody();
        }
        if (null !== $v = $this->image) {
            $payload['image'] = $v->requestBody();
        }

        return $payload;
    }
}
