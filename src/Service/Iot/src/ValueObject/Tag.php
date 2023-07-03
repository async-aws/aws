<?php

namespace AsyncAws\Iot\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A set of key/value pairs that are used to manage the resource.
 */
final class Tag
{
    /**
     * The tag's key.
     *
     * @var string
     */
    private $key;

    /**
     * The tag's value.
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   Key: string,
     *   Value?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Key: string,
     *   Value?: null|string,
     * }|Tag $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->key;
        $payload['Key'] = $v;
        if (null !== $v = $this->value) {
            $payload['Value'] = $v;
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
