<?php

namespace AsyncAws\Sqs\Enum;

final class MessageSystemAttributeName
{
    public const ALL = 'All';
    public const APPROXIMATE_FIRST_RECEIVE_TIMESTAMP = 'ApproximateFirstReceiveTimestamp';
    public const APPROXIMATE_RECEIVE_COUNT = 'ApproximateReceiveCount';
    public const AWSTRACE_HEADER = 'AWSTraceHeader';
    public const DEAD_LETTER_QUEUE_SOURCE_ARN = 'DeadLetterQueueSourceArn';
    public const MESSAGE_DEDUPLICATION_ID = 'MessageDeduplicationId';
    public const MESSAGE_GROUP_ID = 'MessageGroupId';
    public const SENDER_ID = 'SenderId';
    public const SENT_TIMESTAMP = 'SentTimestamp';
    public const SEQUENCE_NUMBER = 'SequenceNumber';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::APPROXIMATE_FIRST_RECEIVE_TIMESTAMP => true,
            self::APPROXIMATE_RECEIVE_COUNT => true,
            self::AWSTRACE_HEADER => true,
            self::DEAD_LETTER_QUEUE_SOURCE_ARN => true,
            self::MESSAGE_DEDUPLICATION_ID => true,
            self::MESSAGE_GROUP_ID => true,
            self::SENDER_ID => true,
            self::SENT_TIMESTAMP => true,
            self::SEQUENCE_NUMBER => true,
        ][$value]);
    }
}
