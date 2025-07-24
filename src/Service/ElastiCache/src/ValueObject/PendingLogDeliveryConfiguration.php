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
     * Refers to slow-log [^1] or engine-log..
     *
     * [^1]: https://redis.io/commands/slowlog
     *
     * @var LogType::*|string|null
     */
    private $logType;

    /**
     * Returns the destination type, either CloudWatch Logs or Kinesis Data Firehose.
     *
     * @var DestinationType::*|string|null
     */
    private $destinationType;

    /**
     * Configuration details of either a CloudWatch Logs destination or Kinesis Data Firehose destination.
     *
     * @var DestinationDetails|null
     */
    private $destinationDetails;

    /**
     * Returns the log format, either JSON or TEXT.
     *
     * @var LogFormat::*|string|null
     */
    private $logFormat;

    /**
     * @param array{
     *   LogType?: null|LogType::*|string,
     *   DestinationType?: null|DestinationType::*|string,
     *   DestinationDetails?: null|DestinationDetails|array,
     *   LogFormat?: null|LogFormat::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logType = $input['LogType'] ?? null;
        $this->destinationType = $input['DestinationType'] ?? null;
        $this->destinationDetails = isset($input['DestinationDetails']) ? DestinationDetails::create($input['DestinationDetails']) : null;
        $this->logFormat = $input['LogFormat'] ?? null;
    }

    /**
     * @param array{
     *   LogType?: null|LogType::*|string,
     *   DestinationType?: null|DestinationType::*|string,
     *   DestinationDetails?: null|DestinationDetails|array,
     *   LogFormat?: null|LogFormat::*|string,
     * }|PendingLogDeliveryConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDestinationDetails(): ?DestinationDetails
    {
        return $this->destinationDetails;
    }

    /**
     * @return DestinationType::*|string|null
     */
    public function getDestinationType(): ?string
    {
        return $this->destinationType;
    }

    /**
     * @return LogFormat::*|string|null
     */
    public function getLogFormat(): ?string
    {
        return $this->logFormat;
    }

    /**
     * @return LogType::*|string|null
     */
    public function getLogType(): ?string
    {
        return $this->logType;
    }
}
