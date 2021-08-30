<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * The configuration details of the CloudWatch Logs destination.
 */
final class CloudWatchLogsDestinationDetails
{
    /**
     * The name of the CloudWatch Logs log group.
     */
    private $logGroup;

    /**
     * @param array{
     *   LogGroup?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logGroup = $input['LogGroup'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLogGroup(): ?string
    {
        return $this->logGroup;
    }
}
