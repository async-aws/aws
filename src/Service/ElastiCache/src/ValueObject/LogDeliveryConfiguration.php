<?php

namespace AsyncAws\ElastiCache\ValueObject;

use AsyncAws\ElastiCache\Enum\DestinationType;
use AsyncAws\ElastiCache\Enum\LogDeliveryConfigurationStatus;
use AsyncAws\ElastiCache\Enum\LogFormat;
use AsyncAws\ElastiCache\Enum\LogType;

/**
 * Returns the destination, format and type of the logs.
 */
final class LogDeliveryConfiguration
{
    /**
     * Refers to slow-log or engine-log.
     *
     * @see https://redis.io/commands/slowlog
     */
    private $logType;

    /**
     * Returns the destination type, either `cloudwatch-logs` or `kinesis-firehose`.
     */
    private $destinationType;

    /**
     * Configuration details of either a CloudWatch Logs destination or Kinesis Data Firehose destination.
     */
    private $destinationDetails;

    /**
     * Returns the log format, either JSON or TEXT.
     */
    private $logFormat;

    /**
     * Returns the log delivery configuration status. Values are one of `enabling` | `disabling` | `modifying` | `active` |
     * `error`.
     */
    private $status;

    /**
     * Returns an error message for the log delivery configuration.
     */
    private $message;

    /**
     * @param array{
     *   LogType?: null|LogType::*,
     *   DestinationType?: null|DestinationType::*,
     *   DestinationDetails?: null|DestinationDetails|array,
     *   LogFormat?: null|LogFormat::*,
     *   Status?: null|LogDeliveryConfigurationStatus::*,
     *   Message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logType = $input['LogType'] ?? null;
        $this->destinationType = $input['DestinationType'] ?? null;
        $this->destinationDetails = isset($input['DestinationDetails']) ? DestinationDetails::create($input['DestinationDetails']) : null;
        $this->logFormat = $input['LogFormat'] ?? null;
        $this->status = $input['Status'] ?? null;
        $this->message = $input['Message'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDestinationDetails(): ?DestinationDetails
    {
        return $this->destinationDetails;
    }

    /**
     * @return DestinationType::*|null
     */
    public function getDestinationType(): ?string
    {
        return $this->destinationType;
    }

    /**
     * @return LogFormat::*|null
     */
    public function getLogFormat(): ?string
    {
        return $this->logFormat;
    }

    /**
     * @return LogType::*|null
     */
    public function getLogType(): ?string
    {
        return $this->logType;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @return LogDeliveryConfigurationStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}
