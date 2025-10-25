<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * A block of content for a message that you pass to, or receive from, a model with the Converse [^1] or ConverseStream
 * [^2] API operations.
 *
 * [^1]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_Converse.html
 * [^2]: https://docs.aws.amazon.com/bedrock/latest/APIReference/API_runtime_ConverseStream.html
 */
final class ContentBlock
{
    /**
     * Text to include in the message.
     *
     * @var string|null
     */
    private $text;

    /**
     * Image to include in the message.
     *
     * > This field is only supported by Anthropic Claude 3 models.
     *
     * @var ImageBlock|null
     */
    private $image;

    /**
     * A document to include in the message.
     *
     * @var DocumentBlock|null
     */
    private $document;

    /**
     * Video to include in the message.
     *
     * @var VideoBlock|null
     */
    private $video;

    /**
     * Information about a tool use request from a model.
     *
     * @var ToolUseBlock|null
     */
    private $toolUse;

    /**
     * The result for a tool request that a model makes.
     *
     * @var ToolResultBlock|null
     */
    private $toolResult;

    /**
     * Contains the content to assess with the guardrail. If you don't specify `guardContent` in a call to the Converse API,
     * the guardrail (if passed in the Converse API) assesses the entire message.
     *
     * For more information, see *Use a guardrail with the Converse API* in the *Amazon Bedrock User Guide*. ```
     * </p>
     * ```
     *
     * @var GuardrailConverseContentBlock|null
     */
    private $guardContent;

    /**
     * Contains content regarding the reasoning that is carried out by the model. Reasoning refers to a Chain of Thought
     * (CoT) that the model generates to enhance the accuracy of its final response.
     *
     * @var ReasoningContentBlock|null
     */
    private $reasoningContent;

    /**
     * @param array{
     *   text?: null|string,
     *   image?: null|ImageBlock|array,
     *   document?: null|DocumentBlock|array,
     *   video?: null|VideoBlock|array,
     *   toolUse?: null|ToolUseBlock|array,
     *   toolResult?: null|ToolResultBlock|array,
     *   guardContent?: null|GuardrailConverseContentBlock|array,
     *   reasoningContent?: null|ReasoningContentBlock|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->text = $input['text'] ?? null;
        $this->image = isset($input['image']) ? ImageBlock::create($input['image']) : null;
        $this->document = isset($input['document']) ? DocumentBlock::create($input['document']) : null;
        $this->video = isset($input['video']) ? VideoBlock::create($input['video']) : null;
        $this->toolUse = isset($input['toolUse']) ? ToolUseBlock::create($input['toolUse']) : null;
        $this->toolResult = isset($input['toolResult']) ? ToolResultBlock::create($input['toolResult']) : null;
        $this->guardContent = isset($input['guardContent']) ? GuardrailConverseContentBlock::create($input['guardContent']) : null;
        $this->reasoningContent = isset($input['reasoningContent']) ? ReasoningContentBlock::create($input['reasoningContent']) : null;
    }

    /**
     * @param array{
     *   text?: null|string,
     *   image?: null|ImageBlock|array,
     *   document?: null|DocumentBlock|array,
     *   video?: null|VideoBlock|array,
     *   toolUse?: null|ToolUseBlock|array,
     *   toolResult?: null|ToolResultBlock|array,
     *   guardContent?: null|GuardrailConverseContentBlock|array,
     *   reasoningContent?: null|ReasoningContentBlock|array,
     * }|ContentBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDocument(): ?DocumentBlock
    {
        return $this->document;
    }

    public function getGuardContent(): ?GuardrailConverseContentBlock
    {
        return $this->guardContent;
    }

    public function getImage(): ?ImageBlock
    {
        return $this->image;
    }

    public function getReasoningContent(): ?ReasoningContentBlock
    {
        return $this->reasoningContent;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getToolResult(): ?ToolResultBlock
    {
        return $this->toolResult;
    }

    public function getToolUse(): ?ToolUseBlock
    {
        return $this->toolUse;
    }

    public function getVideo(): ?VideoBlock
    {
        return $this->video;
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
        if (null !== $v = $this->image) {
            $payload['image'] = $v->requestBody();
        }
        if (null !== $v = $this->document) {
            $payload['document'] = $v->requestBody();
        }
        if (null !== $v = $this->video) {
            $payload['video'] = $v->requestBody();
        }
        if (null !== $v = $this->toolUse) {
            $payload['toolUse'] = $v->requestBody();
        }
        if (null !== $v = $this->toolResult) {
            $payload['toolResult'] = $v->requestBody();
        }
        if (null !== $v = $this->guardContent) {
            $payload['guardContent'] = $v->requestBody();
        }
        if (null !== $v = $this->reasoningContent) {
            $payload['reasoningContent'] = $v->requestBody();
        }

        return $payload;
    }
}
