<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use Force include renditions to specify one or more resolutions to include your ABR stack. * (Recommended) To
 * optimize automated ABR, specify as few resolutions as possible. * (Required) The number of resolutions that you
 * specify must be equal to, or less than, the Max renditions setting. * If you specify a Min top rendition size rule,
 * specify at least one resolution that is equal to, or greater than, Min top rendition size. * If you specify a Min
 * bottom rendition size rule, only specify resolutions that are equal to, or greater than, Min bottom rendition size. *
 * If you specify a Force include renditions rule, do not specify a separate rule for Allowed renditions. * Note: The
 * ABR stack may include other resolutions that you do not specify here, depending on the Max renditions setting.
 */
final class ForceIncludeRenditionSize
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
     * }|ForceIncludeRenditionSize $input
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
