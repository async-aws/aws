<?php

namespace AsyncAws\EventBridge\ValueObject;

/**
 * Represents an event that failed to be submitted.
 */
final class PutEventsResultEntry
{
    /**
     * The ID of the event.
     */
    private $eventId;

    /**
     * The error code that indicates why the event submission failed.
     */
    private $errorCode;

    /**
     * The error message that explains why the event submission failed.
     */
    private $errorMessage;

    /**
     * @param array{
     *   EventId?: null|string,
     *   ErrorCode?: null|string,
     *   ErrorMessage?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->eventId = $input['EventId'] ?? null;
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->errorMessage = $input['ErrorMessage'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getEventId(): ?string
    {
        return $this->eventId;
    }
}
