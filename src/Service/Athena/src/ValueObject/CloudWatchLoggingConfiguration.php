<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Configuration settings for delivering logs to Amazon CloudWatch log groups.
 */
final class CloudWatchLoggingConfiguration
{
    /**
     * Enables CloudWatch logging.
     *
     * @var bool
     */
    private $enabled;

    /**
     * The name of the log group in Amazon CloudWatch Logs where you want to publish your logs.
     *
     * @var string|null
     */
    private $logGroup;

    /**
     * Prefix for the CloudWatch log stream name.
     *
     * @var string|null
     */
    private $logStreamNamePrefix;

    /**
     * The types of logs that you want to publish to CloudWatch.
     *
     * @var array<string, string[]>|null
     */
    private $logTypes;

    /**
     * @param array{
     *   Enabled: bool,
     *   LogGroup?: string|null,
     *   LogStreamNamePrefix?: string|null,
     *   LogTypes?: array<string, array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? $this->throwException(new InvalidArgument('Missing required field "Enabled".'));
        $this->logGroup = $input['LogGroup'] ?? null;
        $this->logStreamNamePrefix = $input['LogStreamNamePrefix'] ?? null;
        $this->logTypes = $input['LogTypes'] ?? null;
    }

    /**
     * @param array{
     *   Enabled: bool,
     *   LogGroup?: string|null,
     *   LogStreamNamePrefix?: string|null,
     *   LogTypes?: array<string, array>|null,
     * }|CloudWatchLoggingConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getLogGroup(): ?string
    {
        return $this->logGroup;
    }

    public function getLogStreamNamePrefix(): ?string
    {
        return $this->logStreamNamePrefix;
    }

    /**
     * @return array<string, string[]>
     */
    public function getLogTypes(): array
    {
        return $this->logTypes ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->enabled;
        $payload['Enabled'] = (bool) $v;
        if (null !== $v = $this->logGroup) {
            $payload['LogGroup'] = $v;
        }
        if (null !== $v = $this->logStreamNamePrefix) {
            $payload['LogStreamNamePrefix'] = $v;
        }
        if (null !== $v = $this->logTypes) {
            if (empty($v)) {
                $payload['LogTypes'] = new \stdClass();
            } else {
                $payload['LogTypes'] = [];
                foreach ($v as $name => $mv) {
                    $index = -1;
                    $payload['LogTypes'][$name] = [];
                    foreach ($mv as $listValue) {
                        ++$index;
                        $payload['LogTypes'][$name][$index] = $listValue;
                    }
                }
            }
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
