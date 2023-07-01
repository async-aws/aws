<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains any warning codes and their count for the job.
 */
final class WarningGroup
{
    /**
     * Warning code that identifies a specific warning in the job. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/warning_codes.html.
     */
    private $code;

    /**
     * The number of times this warning occurred in the job.
     */
    private $count;

    /**
     * @param array{
     *   Code: int,
     *   Count: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->code = $input['Code'] ?? $this->throwException(new InvalidArgument('Missing required field "Code".'));
        $this->count = $input['Count'] ?? $this->throwException(new InvalidArgument('Missing required field "Count".'));
    }

    /**
     * @param array{
     *   Code: int,
     *   Count: int,
     * }|WarningGroup $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
