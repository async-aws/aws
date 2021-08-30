<?php

namespace AsyncAws\ElastiCache\ValueObject;

use AsyncAws\ElastiCache\Enum\DestinationType;
use AsyncAws\ElastiCache\Enum\LogFormat;
use AsyncAws\ElastiCache\Enum\LogType;

/**
 * The log delivery configurations being modified.
 */
final class PendingLogDeliveryConfiguration
{
    /**
     * Refers to slow-log.
     *
     * @see https://redis.io/commands/slowlog
     */
    private $logType;

    /**
     * Returns the destination type, either CloudWatch Logs or Kinesis Data Firehose.
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
     * @param array{
     *   LogType?: null|LogType::*,
     *   DestinationType?: null|DestinationType::*,
     *   DestinationDetails?: null|DestinationDetails|array,
     *   LogFormat?: null|LogFormat::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logType = $input['LogType'] ?? null;
        $this->destinationType = $input['DestinationType'] ?? null;
        $this->destinationDetails = isset($input['DestinationDetails']) ? DestinationDetails::create($input['DestinationDetails']) : null;
        $this->logFormat = $input['LogFormat'] ?? null;
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
}
