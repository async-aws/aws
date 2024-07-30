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
     * The current status of the logs in CloudWatch Logs for a build project. Valid values are:
     *
     * - `ENABLED`: CloudWatch Logs are enabled for this build project.
     * - `DISABLED`: CloudWatch Logs are not enabled for this build project.
     *
     * @var LogsConfigStatusType::*
     */
    private $status;

    /**
     * The group name of the logs in CloudWatch Logs. For more information, see Working with Log Groups and Log Streams
     * [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/Working-with-log-groups-and-streams.html
     *
     * @var string|null
     */
    private $groupName;

    /**
     * The prefix of the stream name of the CloudWatch Logs. For more information, see Working with Log Groups and Log
     * Streams [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/Working-with-log-groups-and-streams.html
     *
     * @var string|null
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
        $this->status = $input['status'] ?? $this->throwException(new InvalidArgument('Missing required field "status".'));
        $this->groupName = $input['groupName'] ?? null;
        $this->streamName = $input['streamName'] ?? null;
    }

    /**
     * @param array{
     *   status: LogsConfigStatusType::*,
     *   groupName?: null|string,
     *   streamName?: null|string,
     * }|CloudWatchLogsConfig $input
     */
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
        $v = $this->status;
        if (!LogsConfigStatusType::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "status" for "%s". The value "%s" is not a valid "LogsConfigStatusType".', __CLASS__, $v));
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
