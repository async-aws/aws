<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\BedrockAgent\Enum\InlineContentType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about content defined inline to ingest into a data source. Choose a `type` and include the field
 * that corresponds to it.
 */
final class InlineContent
{
    /**
     * The type of inline content to define.
     *
     * @var InlineContentType::*
     */
    private $type;

    /**
     * Contains information about content defined inline in bytes.
     *
     * @var ByteContentDoc|null
     */
    private $byteContent;

    /**
     * Contains information about content defined inline in text.
     *
     * @var TextContentDoc|null
     */
    private $textContent;

    /**
     * @param array{
     *   type: InlineContentType::*,
     *   byteContent?: ByteContentDoc|array|null,
     *   textContent?: TextContentDoc|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['type'] ?? $this->throwException(new InvalidArgument('Missing required field "type".'));
        $this->byteContent = isset($input['byteContent']) ? ByteContentDoc::create($input['byteContent']) : null;
        $this->textContent = isset($input['textContent']) ? TextContentDoc::create($input['textContent']) : null;
    }

    /**
     * @param array{
     *   type: InlineContentType::*,
     *   byteContent?: ByteContentDoc|array|null,
     *   textContent?: TextContentDoc|array|null,
     * }|InlineContent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getByteContent(): ?ByteContentDoc
    {
        return $this->byteContent;
    }

    public function getTextContent(): ?TextContentDoc
    {
        return $this->textContent;
    }

    /**
     * @return InlineContentType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->type;
        if (!InlineContentType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "type" for "%s". The value "%s" is not a valid "InlineContentType".', __CLASS__, $v));
        }
        $payload['type'] = $v;
        if (null !== $v = $this->byteContent) {
            $payload['byteContent'] = $v->requestBody();
        }
        if (null !== $v = $this->textContent) {
            $payload['textContent'] = $v->requestBody();
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
