<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the settings used to enable or disable Time to Live (TTL) for the specified table.
 */
final class TimeToLiveSpecification
{
    /**
     * Indicates whether TTL is to be enabled (true) or disabled (false) on the table.
     *
     * @var bool
     */
    private $enabled;

    /**
     * The name of the TTL attribute used to store the expiration time for items in the table.
     *
     * @var string
     */
    private $attributeName;

    /**
     * @param array{
     *   Enabled: bool,
     *   AttributeName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "Enabled".'));
        $this->attributeName = $input['AttributeName'] ?? $this->throwException(new InvalidArgument('Missing required field "AttributeName".'));
    }

    /**
     * @param array{
     *   Enabled: bool,
     *   AttributeName: string,
     * }|TimeToLiveSpecification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->enabled;
        $payload['Enabled'] = (bool) $v;
        $v = $this->attributeName;
        $payload['AttributeName'] = $v;

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
