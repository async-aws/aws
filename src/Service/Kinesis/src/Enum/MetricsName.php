<?php

namespace AsyncAws\Kinesis\Enum;

final class MetricsName
{
    public const ALL = 'ALL';
    public const INCOMING_BYTES = 'IncomingBytes';
    public const INCOMING_RECORDS = 'IncomingRecords';
    public const ITERATOR_AGE_MILLISECONDS = 'IteratorAgeMilliseconds';
    public const OUTGOING_BYTES = 'OutgoingBytes';
    public const OUTGOING_RECORDS = 'OutgoingRecords';
    public const READ_PROVISIONED_THROUGHPUT_EXCEEDED = 'ReadProvisionedThroughputExceeded';
    public const WRITE_PROVISIONED_THROUGHPUT_EXCEEDED = 'WriteProvisionedThroughputExceeded';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::INCOMING_BYTES => true,
            self::INCOMING_RECORDS => true,
            self::ITERATOR_AGE_MILLISECONDS => true,
            self::OUTGOING_BYTES => true,
            self::OUTGOING_RECORDS => true,
            self::READ_PROVISIONED_THROUGHPUT_EXCEEDED => true,
            self::WRITE_PROVISIONED_THROUGHPUT_EXCEEDED => true,
        ][$value]);
    }
}
