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
     *
     * @var int
     */
    private $timestamp;

    /**
     * The raw event message. Each log event can be no larger than 1 MB.
     *
     * @var string
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
        $this->timestamp = $input['timestamp'] ?? $this->throwException(new InvalidArgument('Missing required field "timestamp".'));
        $this->message = $input['message'] ?? $this->throwException(new InvalidArgument('Missing required field "message".'));
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
        $v = $this->timestamp;
        $payload['timestamp'] = $v;
        $v = $this->message;
        $payload['message'] = $v;

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
