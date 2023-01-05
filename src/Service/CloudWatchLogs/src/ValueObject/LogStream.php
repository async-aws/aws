<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * Represents a log stream, which is a sequence of log events from a single emitter of logs.
 */
final class LogStream
{
    /**
     * The name of the log stream.
     */
    private $logStreamName;

    /**
     * The creation time of the stream, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`.
     */
    private $creationTime;

    /**
     * The time of the first event, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`.
     */
    private $firstEventTimestamp;

    /**
     * The time of the most recent log event in the log stream in CloudWatch Logs. This number is expressed as the number of
     * milliseconds after `Jan 1, 1970 00:00:00 UTC`. The `lastEventTime` value updates on an eventual consistency basis. It
     * typically updates in less than an hour from ingestion, but in rare situations might take longer.
     */
    private $lastEventTimestamp;

    /**
     * The ingestion time, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC` The `lastIngestionTime`
     * value updates on an eventual consistency basis. It typically updates in less than an hour after ingestion, but in
     * rare situations might take longer.
     */
    private $lastIngestionTime;

    /**
     * The sequence token.
     */
    private $uploadSequenceToken;

    /**
     * The Amazon Resource Name (ARN) of the log stream.
     */
    private $arn;

    /**
     * The number of bytes stored.
     */
    private $storedBytes;

    /**
     * @param array{
     *   logStreamName?: null|string,
     *   creationTime?: null|string,
     *   firstEventTimestamp?: null|string,
     *   lastEventTimestamp?: null|string,
     *   lastIngestionTime?: null|string,
     *   uploadSequenceToken?: null|string,
     *   arn?: null|string,
     *   storedBytes?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logStreamName = $input['logStreamName'] ?? null;
        $this->creationTime = $input['creationTime'] ?? null;
        $this->firstEventTimestamp = $input['firstEventTimestamp'] ?? null;
        $this->lastEventTimestamp = $input['lastEventTimestamp'] ?? null;
        $this->lastIngestionTime = $input['lastIngestionTime'] ?? null;
        $this->uploadSequenceToken = $input['uploadSequenceToken'] ?? null;
        $this->arn = $input['arn'] ?? null;
        $this->storedBytes = $input['storedBytes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getCreationTime(): ?string
    {
        return $this->creationTime;
    }

    public function getFirstEventTimestamp(): ?string
    {
        return $this->firstEventTimestamp;
    }

    public function getLastEventTimestamp(): ?string
    {
        return $this->lastEventTimestamp;
    }

    public function getLastIngestionTime(): ?string
    {
        return $this->lastIngestionTime;
    }

    public function getLogStreamName(): ?string
    {
        return $this->logStreamName;
    }

    /**
     * @deprecated
     */
    public function getStoredBytes(): ?string
    {
        @trigger_error(sprintf('The property "storedBytes" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

        return $this->storedBytes;
    }

    public function getUploadSequenceToken(): ?string
    {
        return $this->uploadSequenceToken;
    }
}
