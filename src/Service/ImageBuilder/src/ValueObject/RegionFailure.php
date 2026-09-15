<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\ImageConfigurationStep;
use AsyncAws\ImageBuilder\Enum\RegionFailureStatus;

/**
 * Contains details about a distribution or image configuration failure for a single Region.
 */
final class RegionFailure
{
    /**
     * The Region where the failure occurred.
     *
     * @var string|null
     */
    private $region;

    /**
     * The failure status for the Region. Indicates whether the process failed, was canceled, or timed out.
     *
     * @var RegionFailureStatus::*|null
     */
    private $status;

    /**
     * The image configuration step where the failure occurred. Image Builder sets this property when the failure happened
     * during post-distribution configuration, such as launch template updates or virtual machine (VM) export. This property
     * doesn't appear for failures that occurred while Image Builder copied the image to the Region.
     *
     * @var ImageConfigurationStep::*|null
     */
    private $imageConfigurationStep;

    /**
     * The error message for the failure in the Region.
     *
     * @var string|null
     */
    private $errorMessage;

    /**
     * The account ID of the account that the image was distributed to in the Region.
     *
     * @var string|null
     */
    private $targetAccountId;

    /**
     * @param array{
     *   region?: string|null,
     *   status?: RegionFailureStatus::*|null,
     *   imageConfigurationStep?: ImageConfigurationStep::*|null,
     *   errorMessage?: string|null,
     *   targetAccountId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->region = $input['region'] ?? null;
        $this->status = $input['status'] ?? null;
        $this->imageConfigurationStep = $input['imageConfigurationStep'] ?? null;
        $this->errorMessage = $input['errorMessage'] ?? null;
        $this->targetAccountId = $input['targetAccountId'] ?? null;
    }

    /**
     * @param array{
     *   region?: string|null,
     *   status?: RegionFailureStatus::*|null,
     *   imageConfigurationStep?: ImageConfigurationStep::*|null,
     *   errorMessage?: string|null,
     *   targetAccountId?: string|null,
     * }|RegionFailure $input
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
     * @return ImageConfigurationStep::*|null
     */
    public function getImageConfigurationStep(): ?string
    {
        return $this->imageConfigurationStep;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @return RegionFailureStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getTargetAccountId(): ?string
    {
        return $this->targetAccountId;
    }
}
