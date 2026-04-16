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
     * @param array{
     *   status?: ImageStatus::*|null,
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
     *   status?: ImageStatus::*|null,
     *   reason?: string|null,
     * }|ImageState $input
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
     * @return ImageStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
}
