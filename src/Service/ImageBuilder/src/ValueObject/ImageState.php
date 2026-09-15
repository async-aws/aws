<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\ImageStatus;

/**
 * Image status and the reason for that status.
 */
final class ImageState
{
    /**
     * The status of the image.
     *
     * @var ImageStatus::*|null
     */
    private $status;

    /**
     * The reason for the status of the image.
     *
     * @var string|null
     */
    private $reason;

    /**
     * The details about the failure, for images that failed to complete. Image Builder only sets this property when the
     * image status is `FAILED`.
     *
     * @var ImageFailureContext|null
     */
    private $failureContext;

    /**
     * @param array{
     *   status?: ImageStatus::*|null,
     *   reason?: string|null,
     *   failureContext?: ImageFailureContext|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->status = $input['status'] ?? null;
        $this->reason = $input['reason'] ?? null;
        $this->failureContext = isset($input['failureContext']) ? ImageFailureContext::create($input['failureContext']) : null;
    }

    /**
     * @param array{
     *   status?: ImageStatus::*|null,
     *   reason?: string|null,
     *   failureContext?: ImageFailureContext|array|null,
     * }|ImageState $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFailureContext(): ?ImageFailureContext
    {
        return $this->failureContext;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @return ImageStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}
