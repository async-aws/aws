<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Information about build logs in CloudWatch Logs.
 */
final class LogsLocation
{
    /**
     * The name of the CloudWatch Logs group for the build logs.
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The name of the CloudWatch Logs stream for the build logs.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The URL to an individual build log in CloudWatch Logs. The log stream is created during the PROVISIONING phase of a
     * build and the `deeplink` will not be valid until it is created.
     *
     * @var string|null
     */
    private $deepLink;

    /**
     * The URL to a build log in an S3 bucket.
     *
     * @var string|null
     */
    private $s3DeepLink;

    /**
     * The ARN of the CloudWatch Logs stream for a build execution. Its format is
     * `arn:${Partition}:logs:${Region}:${Account}:log-group:${LogGroupName}:log-stream:${LogStreamName}`. The CloudWatch
     * Logs stream is created during the PROVISIONING phase of a build and the ARN will not be valid until it is created.
     * For more information, see Resources Defined by CloudWatch Logs [^1].
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/list_amazoncloudwatchlogs.html#amazoncloudwatchlogs-resources-for-iam-policies
     *
     * @var string|null
     */
    private $cloudWatchLogsArn;

    /**
     * The ARN of S3 logs for a build project. Its format is `arn:${Partition}:s3:::${BucketName}/${ObjectName}`. For more
     * information, see Resources Defined by Amazon S3 [^1].
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/list_amazons3.html#amazons3-resources-for-iam-policies
     *
     * @var string|null
     */
    private $s3LogsArn;

    /**
     * Information about CloudWatch Logs for a build project.
     *
     * @var CloudWatchLogsConfig|null
     */
    private $cloudWatchLogs;

    /**
     * Information about S3 logs for a build project.
     *
     * @var S3LogsConfig|null
     */
    private $s3Logs;

    /**
     * @param array{
     *   groupName?: string|null,
     *   streamName?: string|null,
     *   deepLink?: string|null,
     *   s3DeepLink?: string|null,
     *   cloudWatchLogsArn?: string|null,
     *   s3LogsArn?: string|null,
     *   cloudWatchLogs?: CloudWatchLogsConfig|array|null,
     *   s3Logs?: S3LogsConfig|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->groupName = $input['groupName'] ?? null;
        $this->streamName = $input['streamName'] ?? null;
        $this->deepLink = $input['deepLink'] ?? null;
        $this->s3DeepLink = $input['s3DeepLink'] ?? null;
        $this->cloudWatchLogsArn = $input['cloudWatchLogsArn'] ?? null;
        $this->s3LogsArn = $input['s3LogsArn'] ?? null;
        $this->cloudWatchLogs = isset($input['cloudWatchLogs']) ? CloudWatchLogsConfig::create($input['cloudWatchLogs']) : null;
        $this->s3Logs = isset($input['s3Logs']) ? S3LogsConfig::create($input['s3Logs']) : null;
    }

    /**
     * @param array{
     *   groupName?: string|null,
     *   streamName?: string|null,
     *   deepLink?: string|null,
     *   s3DeepLink?: string|null,
     *   cloudWatchLogsArn?: string|null,
     *   s3LogsArn?: string|null,
     *   cloudWatchLogs?: CloudWatchLogsConfig|array|null,
     *   s3Logs?: S3LogsConfig|array|null,
     * }|LogsLocation $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCloudWatchLogs(): ?CloudWatchLogsConfig
    {
        return $this->cloudWatchLogs;
    }

    public function getCloudWatchLogsArn(): ?string
    {
        return $this->cloudWatchLogsArn;
    }

    public function getDeepLink(): ?string
    {
        return $this->deepLink;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function getS3DeepLink(): ?string
    {
        return $this->s3DeepLink;
    }

    public function getS3Logs(): ?S3LogsConfig
    {
        return $this->s3Logs;
    }

    public function getS3LogsArn(): ?string
    {
        return $this->s3LogsArn;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }
}
