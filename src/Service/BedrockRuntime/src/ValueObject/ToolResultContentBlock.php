<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The tool result content block.
 */
final class ToolResultContentBlock
{
    /**
     * A tool result that is JSON format data.
     *
     * @var Document|null
     */
    private $json;

    /**
     * A tool result that is text.
     *
     * @var string|null
     */
    private $text;

    /**
     * A tool result that is an image.
     *
     * > This field is only supported by Anthropic Claude 3 models.
     *
     * @var ImageBlock|null
     */
    private $image;

    /**
     * A tool result that is a document.
     *
     * @var DocumentBlock|null
     */
    private $document;

    /**
     * A tool result that is video.
     *
     * @var VideoBlock|null
     */
    private $video;

    /**
     * @param array{
     *   json?: null|Document|array,
     *   text?: null|string,
     *   image?: null|ImageBlock|array,
     *   document?: null|DocumentBlock|array,
     *   video?: null|VideoBlock|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->json = isset($input['json']) ? Document::create($input['json']) : null;
        $this->text = $input['text'] ?? null;
        $this->image = isset($input['image']) ? ImageBlock::create($input['image']) : null;
        $this->document = isset($input['document']) ? DocumentBlock::create($input['document']) : null;
        $this->video = isset($input['video']) ? VideoBlock::create($input['video']) : null;
    }

    /**
     * @param array{
     *   json?: null|Document|array,
     *   text?: null|string,
     *   image?: null|ImageBlock|array,
     *   document?: null|DocumentBlock|array,
     *   video?: null|VideoBlock|array,
     * }|ToolResultContentBlock $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDocument(): ?DocumentBlock
    {
        return $this->document;
    }

    public function getImage(): ?ImageBlock
    {
        return $this->image;
    }

    public function getJson(): ?Document
    {
        return $this->json;
    }

    public function getText(): ?string
    {
        return $this->text;
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
        if (null !== $v = $this->json) {
            $payload['json'] = $v->requestBody();
        }
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

        return $payload;
    }
}
