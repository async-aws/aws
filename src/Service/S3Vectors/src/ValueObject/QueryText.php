<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A full-text search query for a single metadata key.
 */
final class QueryText
{
    /**
     * The full-text query string.
     *
     * @var string
     */
    private $text;

    /**
     * The searchable metadata key to query.
     *
     * @var string
     */
    private $metadataKey;

    /**
     * @param array{
     *   text: string,
     *   metadataKey: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->text = $input['text'] ?? $this->throwException(new InvalidArgument('Missing required field "text".'));
        $this->metadataKey = $input['metadataKey'] ?? $this->throwException(new InvalidArgument('Missing required field "metadataKey".'));
    }

    /**
     * @param array{
     *   text: string,
     *   metadataKey: string,
     * }|QueryText $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMetadataKey(): string
    {
        return $this->metadataKey;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->text;
        $payload['text'] = $v;
        $v = $this->metadataKey;
        $payload['metadataKey'] = $v;

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
