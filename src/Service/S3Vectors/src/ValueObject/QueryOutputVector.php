<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The attributes of a vector in the approximate nearest neighbor search.
 */
final class QueryOutputVector
{
    /**
     * The measure of similarity between the vector in the response and the query vector.
     *
     * @var float|null
     */
    private $distance;

    /**
     * The key of the vector in the approximate nearest neighbor search.
     *
     * @var string
     */
    private $key;

    /**
     * The metadata associated with the vector, if requested.
     *
     * @var bool|string|int|float|list<mixed>|array<string, mixed>|null
     */
    private $metadata;

    /**
     * @param array{
     *   distance?: float|null,
     *   key: string,
     *   metadata?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->distance = $input['distance'] ?? null;
        $this->key = $input['key'] ?? $this->throwException(new InvalidArgument('Missing required field "key".'));
        $this->metadata = $input['metadata'] ?? null;
    }

    /**
     * @param array{
     *   distance?: float|null,
     *   key: string,
     *   metadata?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     * }|QueryOutputVector $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistance(): ?float
    {
        return $this->distance;
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
