<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the settings used to enable or disable Time to Live for the specified table.
 */
final class TimeToLiveSpecification
{
    /**
     * Indicates whether TTL is to be enabled (true) or disabled (false) on the table.
     */
    private $Enabled;

    /**
     * The name of the TTL attribute used to store the expiration time for items in the table.
     */
    private $AttributeName;

    /**
     * @param array{
     *   Enabled: bool,
     *   AttributeName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Enabled = $input['Enabled'] ?? null;
        $this->AttributeName = $input['AttributeName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): string
    {
        return $this->AttributeName;
    }

    public function getEnabled(): bool
    {
        return $this->Enabled;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Enabled) {
            throw new InvalidArgument(sprintf('Missing parameter "Enabled" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Enabled'] = (bool) $v;
        if (null === $v = $this->AttributeName) {
            throw new InvalidArgument(sprintf('Missing parameter "AttributeName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AttributeName'] = $v;

        return $payload;
    }
}
