<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Contains details about the output's video stream.
 */
final class VideoDetail
{
    /**
     * Height in pixels for the output.
     */
    private $heightInPx;

    /**
     * Width in pixels for the output.
     */
    private $widthInPx;

    /**
     * @param array{
     *   HeightInPx?: null|int,
     *   WidthInPx?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->heightInPx = $input['HeightInPx'] ?? null;
        $this->widthInPx = $input['WidthInPx'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeightInPx(): ?int
    {
        return $this->heightInPx;
    }

    public function getWidthInPx(): ?int
    {
        return $this->widthInPx;
    }
}
