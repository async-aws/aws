<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about the identifier of the document to ingest into a custom data source.
 */
final class CustomDocumentIdentifier
{
    /**
     * The identifier of the document to ingest into a custom data source.
     *
     * @var string
     */
    private $id;

    /**
     * @param array{
     *   id: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['id'] ?? $this->throwException(new InvalidArgument('Missing required field "id".'));
    }

    /**
     * @param array{
     *   id: string,
     * }|CustomDocumentIdentifier $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->id;
        $payload['id'] = $v;

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
