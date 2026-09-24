<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\ImageScanStatus;

/**
 * Shows the vulnerability scan status for a specific image, and the reason for that status.
 */
final class ImageScanState
{
    /**
     * The current state of vulnerability scans for the image. The scan starts as `PENDING` and moves through `SCANNING` and
     * `COLLECTING` to `COMPLETED`. Image Builder sets the status to `ABANDONED` if the image reaches a terminal state
     * before the scan finding collection completes. A scan can also end as `FAILED` or `TIMED_OUT`.
     *
     * @var ImageScanStatus::*|null
     */
    private $status;

    /**
     * The reason for the scan status for the image.
     *
     * @var string|null
     */
    private $reason;

    /**
     * @param array{
     *   status?: ImageScanStatus::*|null,
     *   reason?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->status = $input['status'] ?? null;
        $this->reason = $input['reason'] ?? null;
    }

    /**
     * @param array{
     *   status?: ImageScanStatus::*|null,
     *   reason?: string|null,
     * }|ImageScanState $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @return ImageScanStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}
