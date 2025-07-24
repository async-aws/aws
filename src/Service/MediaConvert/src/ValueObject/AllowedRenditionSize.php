<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\RequiredFlag;

/**
 * Use Allowed renditions to specify a list of possible resolutions in your ABR stack. * MediaConvert will create an ABR
 * stack exclusively from the list of resolutions that you specify. * Some resolutions in the Allowed renditions list
 * may not be included, however you can force a resolution to be included by setting Required to ENABLED. * You must
 * specify at least one resolution that is greater than or equal to any resolutions that you specify in Min top
 * rendition size or Min bottom rendition size. * If you specify Allowed renditions, you must not specify a separate
 * rule for Force include renditions.
 */
final class AllowedRenditionSize
{
    /**
     * Use Height to define the video resolution height, in pixels, for this rule.
     *
     * @var int|null
     */
    private $height;

    /**
     * Set to ENABLED to force a rendition to be included.
     *
     * @var RequiredFlag::*|string|null
     */
    private $required;

    /**
     * Use Width to define the video resolution width, in pixels, for this rule.
     *
     * @var int|null
     */
    private $width;

    /**
     * @param array{
     *   Height?: null|int,
     *   Required?: null|RequiredFlag::*|string,
     *   Width?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->height = $input['Height'] ?? null;
        $this->required = $input['Required'] ?? null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   Height?: null|int,
     *   Required?: null|RequiredFlag::*|string,
     *   Width?: null|int,
     * }|AllowedRenditionSize $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return RequiredFlag::*|string|null
     */
    public function getRequired(): ?string
    {
        return $this->required;
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
        if (null !== $v = $this->required) {
            if (!RequiredFlag::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "required" for "%s". The value "%s" is not a valid "RequiredFlag".', __CLASS__, $v));
            }
            $payload['required'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
        }

        return $payload;
    }
}
