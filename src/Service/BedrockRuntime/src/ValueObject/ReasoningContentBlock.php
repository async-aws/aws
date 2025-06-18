<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * Contains content regarding the reasoning that is carried out by the model with respect to the content in the content
 * block. Reasoning refers to a Chain of Thought (CoT) that the model generates to enhance the accuracy of its final
 * response.
 */
final class ReasoningContentBlock
{
    /**
     * The reasoning that the model used to return the output.
     *
     * @var ReasoningTextBlock|null
     */
    private $reasoningText;

    /**
     * The content in the reasoning that was encrypted by the model provider for safety reasons. The encryption doesn't
     * affect the quality of responses.
     *
     * @var string|null
     */
    private $redactedContent;

    /**
     * @param array{
     *   reasoningText?: null|ReasoningTextBlock|array,
     *   redactedContent?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->reasoningText = isset($input['reasoningText']) ? ReasoningTextBlock::create($input['reasoningText']) : null;
        $this->redactedContent = $input['redactedContent'] ?? null;
    }

    /**
     * @param array{
     *   reasoningText?: null|ReasoningTextBlock|array,
     *   redactedContent?: null|string,
     * }|ReasoningContentBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReasoningText(): ?ReasoningTextBlock
    {
        return $this->reasoningText;
    }

    public function getRedactedContent(): ?string
    {
        return $this->redactedContent;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->reasoningText) {
            $payload['reasoningText'] = $v->requestBody();
        }
        if (null !== $v = $this->redactedContent) {
            $payload['redactedContent'] = base64_encode($v);
        }

        return $payload;
    }
}
