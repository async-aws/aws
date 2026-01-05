<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The attributes of a vector to add to a vector index.
 */
final class PutInputVector
{
    /**
     * The name of the vector. The key uniquely identifies the vector in a vector index.
     *
     * @var string
     */
    private $key;

    /**
     * The vector data of the vector.
     *
     * Vector dimensions must match the dimension count that's configured for the vector index.
     *
     * - For the `cosine` distance metric, zero vectors (vectors containing all zeros) aren't allowed.
     * - For both `cosine` and `euclidean` distance metrics, vector data must contain only valid floating-point values.
     *   Invalid values such as NaN (Not a Number) or Infinity aren't allowed.
     *
     * @var VectorData
     */
    private $data;

    /**
     * Metadata about the vector. All metadata entries undergo validation to ensure they meet the format requirements for
     * size and data types.
     *
     * @var bool|string|int|float|list<mixed>|array<string, mixed>|null
     */
    private $metadata;

    /**
     * @param array{
     *   key: string,
     *   data: VectorData|array,
     *   metadata?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['key'] ?? $this->throwException(new InvalidArgument('Missing required field "key".'));
        $this->data = isset($input['data']) ? VectorData::create($input['data']) : $this->throwException(new InvalidArgument('Missing required field "data".'));
        $this->metadata = $input['metadata'] ?? null;
    }

    /**
     * @param array{
     *   key: string,
     *   data: VectorData|array,
     *   metadata?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     * }|PutInputVector $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): VectorData
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
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->key;
        $payload['key'] = $v;
        $v = $this->data;
        $payload['data'] = $v->requestBody();
        if (null !== $v = $this->metadata) {
            $payload['metadata'] = $v;
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
