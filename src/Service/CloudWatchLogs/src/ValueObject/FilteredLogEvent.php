<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * Represents a matched event.
 */
final class FilteredLogEvent
{
    /**
     * The name of the log stream to which this event belongs.
     *
     * @var string|null
     */
    private $logStreamName;

    /**
     * The time the event occurred, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`.
     *
     * @var int|null
     */
    private $timestamp;

    /**
     * The data contained in the log event.
     *
     * @var string|null
     */
    private $message;

    /**
     * The time the event was ingested, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`.
     *
     * @var int|null
     */
    private $ingestionTime;

    /**
     * The ID of the event.
     *
     * @var string|null
     */
    private $eventId;

    /**
     * @param array{
     *   logStreamName?: string|null,
     *   timestamp?: int|null,
     *   message?: string|null,
     *   ingestionTime?: int|null,
     *   eventId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logStreamName = $input['logStreamName'] ?? null;
        $this->timestamp = $input['timestamp'] ?? null;
        $this->message = $input['message'] ?? null;
        $this->ingestionTime = $input['ingestionTime'] ?? null;
        $this->eventId = $input['eventId'] ?? null;
    }

    /**
     * @param array{
     *   logStreamName?: string|null,
     *   timestamp?: int|null,
     *   message?: string|null,
     *   ingestionTime?: int|null,
     *   eventId?: string|null,
     * }|FilteredLogEvent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEventId(): ?string
    {
        return $this->eventId;
    }

    public function getIngestionTime(): ?int
    {
        return $this->ingestionTime;
    }

    public function getLogStreamName(): ?string
    {
        return $this->logStreamName;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }
}
