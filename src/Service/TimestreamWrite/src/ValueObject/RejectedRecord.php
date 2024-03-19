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
     *
     * @var int|null
     */
    private $recordIndex;

    /**
     * The reason why a record was not successfully inserted into Timestream. Possible causes of failure include:
     *
     * - Records with duplicate data where there are multiple records with the same dimensions, timestamps, and measure
     *   names but:
     *
     *   - Measure values are different
     *   - Version is not present in the request, *or* the value of version in the new record is equal to or lower than the
     *     existing value
     *
     *   If Timestream rejects data for this case, the `ExistingVersion` field in the `RejectedRecords` response will
     *   indicate the current record’s version. To force an update, you can resend the request with a version for the
     *   record set to a value greater than the `ExistingVersion`.
     * - Records with timestamps that lie outside the retention duration of the memory store.
     *
     *   > When the retention window is updated, you will receive a `RejectedRecords` exception if you immediately try to
     *   > ingest data within the new window. To avoid a `RejectedRecords` exception, wait until the duration of the new
     *   > window to ingest new data. For further information, see Best Practices for Configuring Timestream [^1] and the
     *   > explanation of how storage works in Timestream [^2].
     *
     * - Records with dimensions or measures that exceed the Timestream defined limits.
     *
     * For more information, see Access Management [^3] in the Timestream Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/best-practices.html#configuration
     * [^2]: https://docs.aws.amazon.com/timestream/latest/developerguide/storage.html
     * [^3]: https://docs.aws.amazon.com/timestream/latest/developerguide/ts-limits.html
     *
     * @var string|null
     */
    private $reason;

    /**
     * The existing version of the record. This value is populated in scenarios where an identical record exists with a
     * higher version than the version in the write request.
     *
     * @var int|null
     */
    private $existingVersion;

    /**
     * @param array{
     *   RecordIndex?: null|int,
     *   Reason?: null|string,
     *   ExistingVersion?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->recordIndex = $input['RecordIndex'] ?? null;
        $this->reason = $input['Reason'] ?? null;
        $this->existingVersion = $input['ExistingVersion'] ?? null;
    }

    /**
     * @param array{
     *   RecordIndex?: null|int,
     *   Reason?: null|string,
     *   ExistingVersion?: null|int,
     * }|RejectedRecord $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExistingVersion(): ?int
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
