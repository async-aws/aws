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
    private $EventId;

    /**
     * The error code that indicates why the event submission failed.
     */
    private $ErrorCode;

    /**
     * The error message that explains why the event submission failed.
     */
    private $ErrorMessage;

    /**
     * @param array{
     *   EventId?: null|string,
     *   ErrorCode?: null|string,
     *   ErrorMessage?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->EventId = $input['EventId'] ?? null;
        $this->ErrorCode = $input['ErrorCode'] ?? null;
        $this->ErrorMessage = $input['ErrorMessage'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCode(): ?string
    {
        return $this->ErrorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->ErrorMessage;
    }

    public function getEventId(): ?string
    {
        return $this->EventId;
    }
}
