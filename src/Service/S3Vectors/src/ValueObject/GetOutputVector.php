<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The attributes of a vector returned by the `GetVectors` operation.
 */
final class GetOutputVector
{
    /**
     * The name of the vector.
     *
     * @var string
     */
    private $key;

    /**
     * The vector data of the vector.
     *
     * @var VectorData|null
     */
    private $data;

    /**
     * Metadata about the vector.
     *
     * @var bool|string|int|float|list<mixed>|array<string, mixed>|null
     */
    private $metadata;

    /**
     * @param array{
     *   key: string,
     *   data?: VectorData|array|null,
     *   metadata?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['key'] ?? $this->throwException(new InvalidArgument('Missing required field "key".'));
        $this->data = isset($input['data']) ? VectorData::create($input['data']) : null;
        $this->metadata = $input['metadata'] ?? null;
    }

    /**
     * @param array{
     *   key: string,
     *   data?: VectorData|array|null,
     *   metadata?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     * }|GetOutputVector $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): ?VectorData
    {
        return $this->data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return bool|string|int|float|list<mixed>|array<string, mixed>|null
     */
    public function getMetadata(): bool|string|int|float|array|null
    {
        return $this->metadata;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
