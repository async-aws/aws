<?php

namespace AsyncAws\Sqs\Enum;

final class QueueAttributeName
{
    public const ALL = 'All';
    public const APPROXIMATE_NUMBER_OF_MESSAGES = 'ApproximateNumberOfMessages';
    public const APPROXIMATE_NUMBER_OF_MESSAGES_DELAYED = 'ApproximateNumberOfMessagesDelayed';
    public const APPROXIMATE_NUMBER_OF_MESSAGES_NOT_VISIBLE = 'ApproximateNumberOfMessagesNotVisible';
    public const CONTENT_BASED_DEDUPLICATION = 'ContentBasedDeduplication';
    public const CREATED_TIMESTAMP = 'CreatedTimestamp';
    public const DEDUPLICATION_SCOPE = 'DeduplicationScope';
    public const DELAY_SECONDS = 'DelaySeconds';
    public const FIFO_QUEUE = 'FifoQueue';
    public const FIFO_THROUGHPUT_LIMIT = 'FifoThroughputLimit';
    public const KMS_DATA_KEY_REUSE_PERIOD_SECONDS = 'KmsDataKeyReusePeriodSeconds';
    public const KMS_MASTER_KEY_ID = 'KmsMasterKeyId';
    public const LAST_MODIFIED_TIMESTAMP = 'LastModifiedTimestamp';
    public const MAXIMUM_MESSAGE_SIZE = 'MaximumMessageSize';
    public const MESSAGE_RETENTION_PERIOD = 'MessageRetentionPeriod';
    public const POLICY = 'Policy';
    public const QUEUE_ARN = 'QueueArn';
    public const RECEIVE_MESSAGE_WAIT_TIME_SECONDS = 'ReceiveMessageWaitTimeSeconds';
    public const REDRIVE_ALLOW_POLICY = 'RedriveAllowPolicy';
    public const REDRIVE_POLICY = 'RedrivePolicy';
    public const SQS_MANAGED_SSE_ENABLED = 'SqsManagedSseEnabled';
    public const VISIBILITY_TIMEOUT = 'VisibilityTimeout';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::APPROXIMATE_NUMBER_OF_MESSAGES => true,
            self::APPROXIMATE_NUMBER_OF_MESSAGES_DELAYED => true,
            self::APPROXIMATE_NUMBER_OF_MESSAGES_NOT_VISIBLE => true,
            self::CONTENT_BASED_DEDUPLICATION => true,
            self::CREATED_TIMESTAMP => true,
            self::DEDUPLICATION_SCOPE => true,
            self::DELAY_SECONDS => true,
            self::FIFO_QUEUE => true,
            self::FIFO_THROUGHPUT_LIMIT => true,
            self::KMS_DATA_KEY_REUSE_PERIOD_SECONDS => true,
            self::KMS_MASTER_KEY_ID => true,
            self::LAST_MODIFIED_TIMESTAMP => true,
            self::MAXIMUM_MESSAGE_SIZE => true,
            self::MESSAGE_RETENTION_PERIOD => true,
            self::POLICY => true,
            self::QUEUE_ARN => true,
            self::RECEIVE_MESSAGE_WAIT_TIME_SECONDS => true,
            self::REDRIVE_ALLOW_POLICY => true,
            self::REDRIVE_POLICY => true,
            self::SQS_MANAGED_SSE_ENABLED => true,
            self::VISIBILITY_TIMEOUT => true,
        ][$value]);
    }
}
