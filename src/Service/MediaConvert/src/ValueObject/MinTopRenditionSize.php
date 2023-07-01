<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use Min top rendition size to specify a minimum size for the highest resolution in your ABR stack. * The highest
 * resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If you specify
 * 1280x720 the highest resolution in your ABR stack will be equal to or greater than 1280x720. * If you specify a value
 * for Max resolution, the value that you specify for Min top rendition size must be less than, or equal to, Max
 * resolution.
 */
final class MinTopRenditionSize
{
    /**
     * Use Height to define the video resolution height, in pixels, for this rule.
     *
     * @var int|null
     */
    private $height;

    /**
     * Use Width to define the video resolution width, in pixels, for this rule.
     *
     * @var int|null
     */
    private $width;

    /**
     * @param array{
     *   Height?: null|int,
     *   Width?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->height = $input['Height'] ?? null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   Height?: null|int,
     *   Width?: null|int,
     * }|MinTopRenditionSize $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->height) {
            $payload['height'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
        }

        return $payload;
    }
}
