<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains the configuration settings for managed log persistence, delivering logs to Amazon S3 buckets, Amazon
 * CloudWatch log groups etc.
 */
final class MonitoringConfiguration
{
    /**
     * Configuration settings for delivering logs to Amazon CloudWatch log groups.
     *
     * @var CloudWatchLoggingConfiguration|null
     */
    private $cloudWatchLoggingConfiguration;

    /**
     * Configuration settings for managed log persistence.
     *
     * @var ManagedLoggingConfiguration|null
     */
    private $managedLoggingConfiguration;

    /**
     * Configuration settings for delivering logs to Amazon S3 buckets.
     *
     * @var S3LoggingConfiguration|null
     */
    private $s3LoggingConfiguration;

    /**
     * @param array{
     *   CloudWatchLoggingConfiguration?: CloudWatchLoggingConfiguration|array|null,
     *   ManagedLoggingConfiguration?: ManagedLoggingConfiguration|array|null,
     *   S3LoggingConfiguration?: S3LoggingConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cloudWatchLoggingConfiguration = isset($input['CloudWatchLoggingConfiguration']) ? CloudWatchLoggingConfiguration::create($input['CloudWatchLoggingConfiguration']) : null;
        $this->managedLoggingConfiguration = isset($input['ManagedLoggingConfiguration']) ? ManagedLoggingConfiguration::create($input['ManagedLoggingConfiguration']) : null;
        $this->s3LoggingConfiguration = isset($input['S3LoggingConfiguration']) ? S3LoggingConfiguration::create($input['S3LoggingConfiguration']) : null;
    }

    /**
     * @param array{
     *   CloudWatchLoggingConfiguration?: CloudWatchLoggingConfiguration|array|null,
     *   ManagedLoggingConfiguration?: ManagedLoggingConfiguration|array|null,
     *   S3LoggingConfiguration?: S3LoggingConfiguration|array|null,
     * }|MonitoringConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCloudWatchLoggingConfiguration(): ?CloudWatchLoggingConfiguration
    {
        return $this->cloudWatchLoggingConfiguration;
    }

    public function getManagedLoggingConfiguration(): ?ManagedLoggingConfiguration
    {
        return $this->managedLoggingConfiguration;
    }

    public function getS3LoggingConfiguration(): ?S3LoggingConfiguration
    {
        return $this->s3LoggingConfiguration;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->cloudWatchLoggingConfiguration) {
            $payload['CloudWatchLoggingConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->managedLoggingConfiguration) {
            $payload['ManagedLoggingConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->s3LoggingConfiguration) {
            $payload['S3LoggingConfiguration'] = $v->requestBody();
        }

        return $payload;
    }
}
