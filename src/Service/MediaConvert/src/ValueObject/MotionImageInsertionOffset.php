<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Specify the offset between the upper-left corner of the video frame and the top left corner of the overlay.
 */
final class MotionImageInsertionOffset
{
    /**
     * Set the distance, in pixels, between the overlay and the left edge of the video frame.
     *
     * @var int|null
     */
    private $imageX;

    /**
     * Set the distance, in pixels, between the overlay and the top edge of the video frame.
     *
     * @var int|null
     */
    private $imageY;

    /**
     * @param array{
     *   ImageX?: int|null,
     *   ImageY?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->imageX = $input['ImageX'] ?? null;
        $this->imageY = $input['ImageY'] ?? null;
    }

    /**
     * @param array{
     *   ImageX?: int|null,
     *   ImageY?: int|null,
     * }|MotionImageInsertionOffset $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getImageX(): ?int
    {
        return $this->imageX;
    }

    public function getImageY(): ?int
    {
        return $this->imageY;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->imageX) {
            $payload['imageX'] = $v;
        }
        if (null !== $v = $this->imageY) {
            $payload['imageY'] = $v;
        }

        return $payload;
    }
}
