<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Contains details about a failure that occurred while Image Builder distributed the image or applied configuration to
 * the distributed image.
 */
final class DistributionFailureContext
{
    /**
     * The error message for the distribution failure.
     *
     * @var string|null
     */
    private $errorMessage;

    /**
     * The details about the failure for each Region where the image didn't finish distribution or configuration.
     *
     * @var RegionFailure[]|null
     */
    private $regionFailures;

    /**
     * @param array{
     *   errorMessage?: string|null,
     *   regionFailures?: array<RegionFailure|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorMessage = $input['errorMessage'] ?? null;
        $this->regionFailures = isset($input['regionFailures']) ? array_map([RegionFailure::class, 'create'], $input['regionFailures']) : null;
    }

    /**
     * @param array{
     *   errorMessage?: string|null,
     *   regionFailures?: array<RegionFailure|array>|null,
     * }|DistributionFailureContext $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return RegionFailure[]
     */
    public function getRegionFailures(): array
    {
        return $this->regionFailures ?? [];
    }
}
