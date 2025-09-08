<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Information about logs for a build project. These can be logs in CloudWatch Logs, built in a specified S3 bucket, or
 * both.
 */
final class LogsConfig
{
    /**
     * Information about CloudWatch Logs for a build project. CloudWatch Logs are enabled by default.
     *
     * @var CloudWatchLogsConfig|null
     */
    private $cloudWatchLogs;

    /**
     * Information about logs built to an S3 bucket for a build project. S3 logs are not enabled by default.
     *
     * @var S3LogsConfig|null
     */
    private $s3Logs;

    /**
     * @param array{
     *   cloudWatchLogs?: CloudWatchLogsConfig|array|null,
     *   s3Logs?: S3LogsConfig|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cloudWatchLogs = isset($input['cloudWatchLogs']) ? CloudWatchLogsConfig::create($input['cloudWatchLogs']) : null;
        $this->s3Logs = isset($input['s3Logs']) ? S3LogsConfig::create($input['s3Logs']) : null;
    }

    /**
     * @param array{
     *   cloudWatchLogs?: CloudWatchLogsConfig|array|null,
     *   s3Logs?: S3LogsConfig|array|null,
     * }|LogsConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCloudWatchLogs(): ?CloudWatchLogsConfig
    {
        return $this->cloudWatchLogs;
    }

    public function getS3Logs(): ?S3LogsConfig
    {
        return $this->s3Logs;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->cloudWatchLogs) {
            $payload['cloudWatchLogs'] = $v->requestBody();
        }
        if (null !== $v = $this->s3Logs) {
            $payload['s3Logs'] = $v->requestBody();
        }

        return $payload;
    }
}
