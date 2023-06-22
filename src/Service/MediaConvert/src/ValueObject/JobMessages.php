<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Provides messages from the service about jobs that you have already successfully submitted.
 */
final class JobMessages
{
    /**
     * List of messages that are informational only and don't indicate a problem with your job.
     */
    private $info;

    /**
     * List of messages that warn about conditions that might cause your job not to run or to fail.
     */
    private $warning;

    /**
     * @param array{
     *   Info?: null|string[],
     *   Warning?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->info = $input['Info'] ?? null;
        $this->warning = $input['Warning'] ?? null;
    }

    /**
     * @param array{
     *   Info?: null|string[],
     *   Warning?: null|string[],
     * }|JobMessages $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getInfo(): array
    {
        return $this->info ?? [];
    }

    /**
     * @return string[]
     */
    public function getWarning(): array
    {
        return $this->warning ?? [];
    }
}
