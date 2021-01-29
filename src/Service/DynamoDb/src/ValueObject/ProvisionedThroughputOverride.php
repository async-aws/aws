<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Replica-specific provisioned throughput. If not described, uses the source table's provisioned throughput settings.
 */
final class ProvisionedThroughputOverride
{
    /**
     * Replica-specific read capacity units. If not specified, uses the source table's read capacity settings.
     */
    private $readCapacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReadCapacityUnits(): ?string
    {
        return $this->readCapacityUnits;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->readCapacityUnits) {
            $payload['ReadCapacityUnits'] = $v;
        }

        return $payload;
    }
}
