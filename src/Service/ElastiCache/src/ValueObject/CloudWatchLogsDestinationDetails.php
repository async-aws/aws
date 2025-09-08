<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * The configuration details of the CloudWatch Logs destination.
 */
final class CloudWatchLogsDestinationDetails
{
    /**
     * The name of the CloudWatch Logs log group.
     *
     * @var string|null
     */
    private $logGroup;

    /**
     * @param array{
     *   LogGroup?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logGroup = $input['LogGroup'] ?? null;
    }

    /**
     * @param array{
     *   LogGroup?: string|null,
     * }|CloudWatchLogsDestinationDetails $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLogGroup(): ?string
    {
        return $this->logGroup;
    }
}
