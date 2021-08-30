<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Configuration details of either a CloudWatch Logs destination or Kinesis Data Firehose destination.
 */
final class DestinationDetails
{
    /**
     * The configuration details of the CloudWatch Logs destination.
     */
    private $cloudWatchLogsDetails;

    /**
     * The configuration details of the Kinesis Data Firehose destination.
     */
    private $kinesisFirehoseDetails;

    /**
     * @param array{
     *   CloudWatchLogsDetails?: null|CloudWatchLogsDestinationDetails|array,
     *   KinesisFirehoseDetails?: null|KinesisFirehoseDestinationDetails|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cloudWatchLogsDetails = isset($input['CloudWatchLogsDetails']) ? CloudWatchLogsDestinationDetails::create($input['CloudWatchLogsDetails']) : null;
        $this->kinesisFirehoseDetails = isset($input['KinesisFirehoseDetails']) ? KinesisFirehoseDestinationDetails::create($input['KinesisFirehoseDetails']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCloudWatchLogsDetails(): ?CloudWatchLogsDestinationDetails
    {
        return $this->cloudWatchLogsDetails;
    }

    public function getKinesisFirehoseDetails(): ?KinesisFirehoseDestinationDetails
    {
        return $this->kinesisFirehoseDetails;
    }
}
