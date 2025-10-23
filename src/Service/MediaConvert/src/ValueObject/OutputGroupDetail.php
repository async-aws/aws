<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Contains details about the output groups specified in the job settings.
 */
final class OutputGroupDetail
{
    /**
     * Details about the output.
     *
     * @var OutputDetail[]|null
     */
    private $outputDetails;

    /**
     * @param array{
     *   OutputDetails?: array<OutputDetail|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->outputDetails = isset($input['OutputDetails']) ? array_map([OutputDetail::class, 'create'], $input['OutputDetails']) : null;
    }

    /**
     * @param array{
     *   OutputDetails?: array<OutputDetail|array>|null,
     * }|OutputGroupDetail $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return OutputDetail[]
     */
    public function getOutputDetails(): array
    {
        return $this->outputDetails ?? [];
    }
}
