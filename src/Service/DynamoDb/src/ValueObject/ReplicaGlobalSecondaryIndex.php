<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the properties of a replica global secondary index.
 */
final class ReplicaGlobalSecondaryIndex
{
    /**
     * The name of the global secondary index.
     */
    private $IndexName;

    /**
     * Replica table GSI-specific provisioned throughput. If not specified, uses the source table GSI's read capacity
     * settings.
     */
    private $ProvisionedThroughputOverride;

    /**
     * @param array{
     *   IndexName: string,
     *   ProvisionedThroughputOverride?: null|ProvisionedThroughputOverride|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->IndexName = $input['IndexName'] ?? null;
        $this->ProvisionedThroughputOverride = isset($input['ProvisionedThroughputOverride']) ? ProvisionedThroughputOverride::create($input['ProvisionedThroughputOverride']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): string
    {
        return $this->IndexName;
    }

    public function getProvisionedThroughputOverride(): ?ProvisionedThroughputOverride
    {
        return $this->ProvisionedThroughputOverride;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->IndexName) {
            throw new InvalidArgument(sprintf('Missing parameter "IndexName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['IndexName'] = $v;
        if (null !== $v = $this->ProvisionedThroughputOverride) {
            $payload['ProvisionedThroughputOverride'] = $v->requestBody();
        }

        return $payload;
    }
}
