<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about a document to ingest into a knowledge base and metadata to associate with it.
 */
final class KnowledgeBaseDocument
{
    /**
     * Contains the metadata to associate with the document.
     *
     * @var DocumentMetadata|null
     */
    private $metadata;

    /**
     * Contains the content of the document.
     *
     * @var DocumentContent
     */
    private $content;

    /**
     * @param array{
     *   metadata?: DocumentMetadata|array|null,
     *   content: DocumentContent|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->metadata = isset($input['metadata']) ? DocumentMetadata::create($input['metadata']) : null;
        $this->content = isset($input['content']) ? DocumentContent::create($input['content']) : $this->throwException(new InvalidArgument('Missing required field "content".'));
    }

    /**
     * @param array{
     *   metadata?: DocumentMetadata|array|null,
     *   content: DocumentContent|array,
     * }|KnowledgeBaseDocument $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContent(): DocumentContent
    {
        return $this->content;
    }

    public function getMetadata(): ?DocumentMetadata
    {
        return $this->metadata;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->metadata) {
            $payload['metadata'] = $v->requestBody();
        }
        $v = $this->content;
        $payload['content'] = $v->requestBody();

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
