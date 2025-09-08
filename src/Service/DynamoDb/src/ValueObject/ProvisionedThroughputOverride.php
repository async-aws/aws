<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Replica-specific provisioned throughput settings. If not specified, uses the source table's provisioned throughput
 * settings.
 */
final class ProvisionedThroughputOverride
{
    /**
     * Replica-specific read capacity units. If not specified, uses the source table's read capacity settings.
     *
     * @var int|null
     */
    private $readCapacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? null;
    }

    /**
     * @param array{
     *   ReadCapacityUnits?: int|null,
     * }|ProvisionedThroughputOverride $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReadCapacityUnits(): ?int
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
