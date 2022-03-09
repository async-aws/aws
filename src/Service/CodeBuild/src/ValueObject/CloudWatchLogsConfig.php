<?php

namespace AsyncAws\CodeBuild\ValueObject;

use AsyncAws\CodeBuild\Enum\LogsConfigStatusType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about CloudWatch Logs for a build project.
 */
final class CloudWatchLogsConfig
{
    /**
     * The current status of the logs in CloudWatch Logs for a build project. Valid values are:.
     */
    private $status;

    /**
     * The group name of the logs in CloudWatch Logs. For more information, see Working with Log Groups and Log Streams.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/Working-with-log-groups-and-streams.html
     */
    private $groupName;

    /**
     * The prefix of the stream name of the CloudWatch Logs. For more information, see Working with Log Groups and Log
     * Streams.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/Working-with-log-groups-and-streams.html
     */
    private $streamName;

    /**
     * @param array{
     *   status: LogsConfigStatusType::*,
     *   groupName?: null|string,
     *   streamName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->status = $input['status'] ?? null;
        $this->groupName = $input['groupName'] ?? null;
        $this->streamName = $input['streamName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    /**
     * @return LogsConfigStatusType::*
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->status) {
            throw new InvalidArgument(sprintf('Missing parameter "status" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!LogsConfigStatusType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "status" for "%s". The value "%s" is not a valid "LogsConfigStatusType".', __CLASS__, $v));
        }
        $payload['status'] = $v;
        if (null !== $v = $this->groupName) {
            $payload['groupName'] = $v;
        }
        if (null !== $v = $this->streamName) {
            $payload['streamName'] = $v;
        }

        return $payload;
    }
}
