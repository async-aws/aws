<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use Min bottom rendition size to specify a minimum size for the lowest resolution in your ABR stack. * The lowest
 * resolution in your ABR stack will be equal to or greater than the value that you enter. For example: If you specify
 * 640x360 the lowest resolution in your ABR stack will be equal to or greater than to 640x360. * If you specify a Min
 * top rendition size rule, the value that you specify for Min bottom rendition size must be less than, or equal to, Min
 * top rendition size.
 */
final class MinBottomRenditionSize
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
     *   Height?: int|null,
     *   Width?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->height = $input['Height'] ?? null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   Height?: int|null,
     *   Width?: int|null,
     * }|MinBottomRenditionSize $input
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
