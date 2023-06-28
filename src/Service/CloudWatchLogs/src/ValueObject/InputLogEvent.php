<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a log event, which is a record of activity that was recorded by the application or resource being
 * monitored.
 */
final class InputLogEvent
{
    /**
     * The time the event occurred, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`.
     */
    private $timestamp;

    /**
     * The raw event message. Each log event can be no larger than 256 KB.
     */
    private $message;

    /**
     * @param array{
     *   timestamp: int,
     *   message: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->timestamp = $input['timestamp'] ?? null;
        $this->message = $input['message'] ?? null;
    }

    /**
     * @param array{
     *   timestamp: int,
     *   message: string,
     * }|InputLogEvent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->timestamp) {
            throw new InvalidArgument(sprintf('Missing parameter "timestamp" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['timestamp'] = $v;
        if (null === $v = $this->message) {
            throw new InvalidArgument(sprintf('Missing parameter "message" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['message'] = $v;

        return $payload;
    }
}
