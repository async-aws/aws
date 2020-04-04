<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class ReplicaGlobalSecondaryIndexDescription
{
    /**
     * The name of the global secondary index.
     */
    private $IndexName;

    /**
     * If not described, uses the source table GSI's read capacity settings.
     */
    private $ProvisionedThroughputOverride;

    /**
     * @param array{
     *   IndexName?: null|string,
     *   ProvisionedThroughputOverride?: null|\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputOverride|array,
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

    public function getIndexName(): ?string
    {
        return $this->IndexName;
    }

    public function getProvisionedThroughputOverride(): ?ProvisionedThroughputOverride
    {
        return $this->ProvisionedThroughputOverride;
    }
}
