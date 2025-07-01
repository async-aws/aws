<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\WitnessStatus;

/**
 * Represents the properties of a witness Region in a MRSC global table.
 */
final class GlobalTableWitnessDescription
{
    /**
     * The name of the Amazon Web Services Region that serves as a witness for the MRSC global table.
     *
     * @var string|null
     */
    private $regionName;

    /**
     * The current status of the witness Region in the MRSC global table.
     *
     * @var WitnessStatus::*|null
     */
    private $witnessStatus;

    /**
     * @param array{
     *   RegionName?: null|string,
     *   WitnessStatus?: null|WitnessStatus::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->regionName = $input['RegionName'] ?? null;
        $this->witnessStatus = $input['WitnessStatus'] ?? null;
    }

    /**
     * @param array{
     *   RegionName?: null|string,
     *   WitnessStatus?: null|WitnessStatus::*,
     * }|GlobalTableWitnessDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    /**
     * @return WitnessStatus::*|null
     */
    public function getWitnessStatus(): ?string
    {
        return $this->witnessStatus;
    }
}
