<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\EventSourceMappingSystemLogLevel;

/**
 * (Amazon MSK, and self-managed Apache Kafka only) The logging configuration for your event source. Use this
 * configuration object to define the level of logs for your event source mapping.
 */
final class EventSourceMappingLoggingConfig
{
    /**
     * The log level you want your event source mapping to use. Lambda event poller only sends system logs at the selected
     * level of detail and lower, where `DEBUG` is the highest level and `WARN` is the lowest. For more information about
     * these metrics, see Event source mapping logging [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/esm-logging.html
     *
     * @var EventSourceMappingSystemLogLevel::*|null
     */
    private $systemLogLevel;

    /**
     * @param array{
     *   SystemLogLevel?: EventSourceMappingSystemLogLevel::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->systemLogLevel = $input['SystemLogLevel'] ?? null;
    }

    /**
     * @param array{
     *   SystemLogLevel?: EventSourceMappingSystemLogLevel::*|null,
     * }|EventSourceMappingLoggingConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EventSourceMappingSystemLogLevel::*|null
     */
    public function getSystemLogLevel(): ?string
    {
        return $this->systemLogLevel;
    }
}
