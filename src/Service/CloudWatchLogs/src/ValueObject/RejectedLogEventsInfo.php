<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * The rejected events.
 */
final class RejectedLogEventsInfo
{
    /**
     * The log events that are too new.
     */
    private $tooNewLogEventStartIndex;

    /**
     * The log events that are too old.
     */
    private $tooOldLogEventEndIndex;

    /**
     * The expired log events.
     */
    private $expiredLogEventEndIndex;

    /**
     * @param array{
     *   tooNewLogEventStartIndex?: null|int,
     *   tooOldLogEventEndIndex?: null|int,
     *   expiredLogEventEndIndex?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tooNewLogEventStartIndex = $input['tooNewLogEventStartIndex'] ?? null;
        $this->tooOldLogEventEndIndex = $input['tooOldLogEventEndIndex'] ?? null;
        $this->expiredLogEventEndIndex = $input['expiredLogEventEndIndex'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExpiredLogEventEndIndex(): ?int
    {
        return $this->expiredLogEventEndIndex;
    }

    public function getTooNewLogEventStartIndex(): ?int
    {
        return $this->tooNewLogEventStartIndex;
    }

    public function getTooOldLogEventEndIndex(): ?int
    {
        return $this->tooOldLogEventEndIndex;
    }
}
