<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * Represents the rejected events.
 */
final class RejectedLogEventsInfo
{
    /**
     * The index of the first log event that is too new. This field is inclusive.
     *
     * @var int|null
     */
    private $tooNewLogEventStartIndex;

    /**
     * The index of the last log event that is too old. This field is exclusive.
     *
     * @var int|null
     */
    private $tooOldLogEventEndIndex;

    /**
     * The expired log events.
     *
     * @var int|null
     */
    private $expiredLogEventEndIndex;

    /**
     * @param array{
     *   tooNewLogEventStartIndex?: int|null,
     *   tooOldLogEventEndIndex?: int|null,
     *   expiredLogEventEndIndex?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tooNewLogEventStartIndex = $input['tooNewLogEventStartIndex'] ?? null;
        $this->tooOldLogEventEndIndex = $input['tooOldLogEventEndIndex'] ?? null;
        $this->expiredLogEventEndIndex = $input['expiredLogEventEndIndex'] ?? null;
    }

    /**
     * @param array{
     *   tooNewLogEventStartIndex?: int|null,
     *   tooOldLogEventEndIndex?: int|null,
     *   expiredLogEventEndIndex?: int|null,
     * }|RejectedLogEventsInfo $input
     */
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
