<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

/**
 * Represents records that were not successfully inserted into Timestream due to data validation issues that must be
 * resolved before reinserting time-series data into the system.
 */
final class RejectedRecord
{
    /**
     * The index of the record in the input request for WriteRecords. Indexes begin with 0.
     */
    private $recordIndex;

    /**
     * The reason why a record was not successfully inserted into Timestream. Possible causes of failure include:.
     */
    private $reason;

    /**
     * The existing version of the record. This value is populated in scenarios where an identical record exists with a
     * higher version than the version in the write request.
     */
    private $existingVersion;

    /**
     * @param array{
     *   RecordIndex?: null|int,
     *   Reason?: null|string,
     *   ExistingVersion?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->recordIndex = $input['RecordIndex'] ?? null;
        $this->reason = $input['Reason'] ?? null;
        $this->existingVersion = $input['ExistingVersion'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExistingVersion(): ?string
    {
        return $this->existingVersion;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function getRecordIndex(): ?int
    {
        return $this->recordIndex;
    }
}
