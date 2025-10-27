<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

use AsyncAws\CloudWatchLogs\Enum\DataProtectionStatus;
use AsyncAws\CloudWatchLogs\Enum\InheritedProperty;
use AsyncAws\CloudWatchLogs\Enum\LogGroupClass;

/**
 * Represents a log group.
 */
final class LogGroup
{
    /**
     * The name of the log group.
     *
     * @var string|null
     */
    private $logGroupName;

    /**
     * The creation time of the log group, expressed as the number of milliseconds after Jan 1, 1970 00:00:00 UTC.
     *
     * @var int|null
     */
    private $creationTime;

    /**
     * @var int|null
     */
    private $retentionInDays;

    /**
     * The number of metric filters.
     *
     * @var int|null
     */
    private $metricFilterCount;

    /**
     * The Amazon Resource Name (ARN) of the log group. This version of the ARN includes a trailing `:*` after the log group
     * name.
     *
     * Use this version to refer to the ARN in IAM policies when specifying permissions for most API actions. The exception
     * is when specifying permissions for TagResource [^1], UntagResource [^2], and ListTagsForResource [^3]. The
     * permissions for those three actions require the ARN version that doesn't include a trailing `:*`.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_TagResource.html
     * [^2]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_UntagResource.html
     * [^3]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_ListTagsForResource.html
     *
     * @var string|null
     */
    private $arn;

    /**
     * The number of bytes stored.
     *
     * @var int|null
     */
    private $storedBytes;

    /**
     * The Amazon Resource Name (ARN) of the KMS key to use when encrypting log data.
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * Displays whether this log group has a protection policy, or whether it had one in the past. For more information, see
     * PutDataProtectionPolicy [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutDataProtectionPolicy.html
     *
     * @var DataProtectionStatus::*|null
     */
    private $dataProtectionStatus;

    /**
     * Displays all the properties that this log group has inherited from account-level settings.
     *
     * @var list<InheritedProperty::*>|null
     */
    private $inheritedProperties;

    /**
     * This specifies the log group class for this log group. There are three classes:
     *
     * - The `Standard` log class supports all CloudWatch Logs features.
     * - The `Infrequent Access` log class supports a subset of CloudWatch Logs features and incurs lower costs.
     * - Use the `Delivery` log class only for delivering Lambda logs to store in Amazon S3 or Amazon Data Firehose. Log
     *   events in log groups in the Delivery class are kept in CloudWatch Logs for only one day. This log class doesn't
     *   offer rich CloudWatch Logs capabilities such as CloudWatch Logs Insights queries.
     *
     * For details about the features supported by the Standard and Infrequent Access classes, see Log classes [^1]
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/CloudWatch_Logs_Log_Classes.html
     *
     * @var LogGroupClass::*|null
     */
    private $logGroupClass;

    /**
     * The Amazon Resource Name (ARN) of the log group. This version of the ARN doesn't include a trailing `:*` after the
     * log group name.
     *
     * Use this version to refer to the ARN in the following situations:
     *
     * - In the `logGroupIdentifier` input field in many CloudWatch Logs APIs.
     * - In the `resourceArn` field in tagging APIs
     * - In IAM policies, when specifying permissions for TagResource [^1], UntagResource [^2], and ListTagsForResource
     *   [^3].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_TagResource.html
     * [^2]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_UntagResource.html
     * [^3]: https://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_ListTagsForResource.html
     *
     * @var string|null
     */
    private $logGroupArn;

    /**
     * @param array{
     *   logGroupName?: string|null,
     *   creationTime?: int|null,
     *   retentionInDays?: int|null,
     *   metricFilterCount?: int|null,
     *   arn?: string|null,
     *   storedBytes?: int|null,
     *   kmsKeyId?: string|null,
     *   dataProtectionStatus?: DataProtectionStatus::*|null,
     *   inheritedProperties?: array<InheritedProperty::*>|null,
     *   logGroupClass?: LogGroupClass::*|null,
     *   logGroupArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
        $this->creationTime = $input['creationTime'] ?? null;
        $this->retentionInDays = $input['retentionInDays'] ?? null;
        $this->metricFilterCount = $input['metricFilterCount'] ?? null;
        $this->arn = $input['arn'] ?? null;
        $this->storedBytes = $input['storedBytes'] ?? null;
        $this->kmsKeyId = $input['kmsKeyId'] ?? null;
        $this->dataProtectionStatus = $input['dataProtectionStatus'] ?? null;
        $this->inheritedProperties = $input['inheritedProperties'] ?? null;
        $this->logGroupClass = $input['logGroupClass'] ?? null;
        $this->logGroupArn = $input['logGroupArn'] ?? null;
    }

    /**
     * @param array{
     *   logGroupName?: string|null,
     *   creationTime?: int|null,
     *   retentionInDays?: int|null,
     *   metricFilterCount?: int|null,
     *   arn?: string|null,
     *   storedBytes?: int|null,
     *   kmsKeyId?: string|null,
     *   dataProtectionStatus?: DataProtectionStatus::*|null,
     *   inheritedProperties?: array<InheritedProperty::*>|null,
     *   logGroupClass?: LogGroupClass::*|null,
     *   logGroupArn?: string|null,
     * }|LogGroup $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getCreationTime(): ?int
    {
        return $this->creationTime;
    }

    /**
     * @return DataProtectionStatus::*|null
     */
    public function getDataProtectionStatus(): ?string
    {
        return $this->dataProtectionStatus;
    }

    /**
     * @return list<InheritedProperty::*>
     */
    public function getInheritedProperties(): array
    {
        return $this->inheritedProperties ?? [];
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getLogGroupArn(): ?string
    {
        return $this->logGroupArn;
    }

    /**
     * @return LogGroupClass::*|null
     */
    public function getLogGroupClass(): ?string
    {
        return $this->logGroupClass;
    }

    public function getLogGroupName(): ?string
    {
        return $this->logGroupName;
    }

    public function getMetricFilterCount(): ?int
    {
        return $this->metricFilterCount;
    }

    public function getRetentionInDays(): ?int
    {
        return $this->retentionInDays;
    }

    public function getStoredBytes(): ?int
    {
        return $this->storedBytes;
    }
}
