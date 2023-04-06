<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains information about whether previous query results were reused for the query.
 */
final class ResultReuseInformation
{
    /**
     * True if a previous query result was reused; false if the result was generated from a new run of the query.
     */
    private $reusedPreviousResult;

    /**
     * @param array{
     *   ReusedPreviousResult: bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->reusedPreviousResult = $input['ReusedPreviousResult'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReusedPreviousResult(): bool
    {
        return $this->reusedPreviousResult;
    }
}
