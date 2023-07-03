<?php

namespace AsyncAws\CloudWatchLogs\ValueObject;

/**
 * Represents the search status of a log stream.
 */
final class SearchedLogStream
{
    /**
     * The name of the log stream.
     *
     * @var string|null
     */
    private $logStreamName;

    /**
     * Indicates whether all the events in this log stream were searched.
     *
     * @var bool|null
     */
    private $searchedCompletely;

    /**
     * @param array{
     *   logStreamName?: null|string,
     *   searchedCompletely?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->logStreamName = $input['logStreamName'] ?? null;
        $this->searchedCompletely = $input['searchedCompletely'] ?? null;
    }

    /**
     * @param array{
     *   logStreamName?: null|string,
     *   searchedCompletely?: null|bool,
     * }|SearchedLogStream $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLogStreamName(): ?string
    {
        return $this->logStreamName;
    }

    public function getSearchedCompletely(): ?bool
    {
        return $this->searchedCompletely;
    }
}
