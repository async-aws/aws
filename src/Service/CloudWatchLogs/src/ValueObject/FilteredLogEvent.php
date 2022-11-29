<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * Represents a matched event.
 */
final class FilteredLogEvent
{
    /**
     * The name of the log stream to which this event belongs.
     */
    private $logStreamName;

    /**
     * The time the event occurred, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`.
     */
    private $timestamp;

    /**
     * The data contained in the log event.
     */
    private $message;

    /**
     * The time the event was ingested, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`.
     */
    private $ingestionTime;

    /**
     * The ID of the event.
     */
    private $eventId;

    /**
     * @param array{
     *   logStreamName?: null|string,
     *   timestamp?: null|string,
     *   message?: null|string,
     *   ingestionTime?: null|string,
     *   eventId?: null|string,
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

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEventId(): ?string
    {
        return $this->eventId;
    }

    public function getIngestionTime(): ?string
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

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }
}
