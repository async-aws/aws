<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Specify YUV limits and RGB tolerances when you set Sample range conversion to Limited range clip.
 */
final class ClipLimits
{
    /**
     * Specify the Maximum RGB color sample range tolerance for your output. MediaConvert corrects any YUV values that, when
     * converted to RGB, would be outside the upper tolerance that you specify. Enter an integer from 90 to 105 as an offset
     * percentage to the maximum possible value. Leave blank to use the default value 100. When you specify a value for
     * Maximum RGB tolerance, you must set Sample range conversion to Limited range clip.
     *
     * @var int|null
     */
    private $maximumRgbTolerance;

    /**
     * Specify the Maximum YUV color sample limit. MediaConvert conforms any pixels in your input above the value that you
     * specify to typical limited range bounds. Enter an integer from 920 to 1023. Leave blank to use the default value 940.
     * The value that you enter applies to 10-bit ranges. For 8-bit ranges, MediaConvert automatically scales this value
     * down. When you specify a value for Maximum YUV, you must set Sample range conversion to Limited range clip.
     *
     * @var int|null
     */
    private $maximumYuv;

    /**
     * Specify the Minimum RGB color sample range tolerance for your output. MediaConvert corrects any YUV values that, when
     * converted to RGB, would be outside the lower tolerance that you specify. Enter an integer from -5 to 10 as an offset
     * percentage to the minimum possible value. Leave blank to use the default value 0. When you specify a value for
     * Minimum RGB tolerance, you must set Sample range conversion to Limited range clip.
     *
     * @var int|null
     */
    private $minimumRgbTolerance;

    /**
     * Specify the Minimum YUV color sample limit. MediaConvert conforms any pixels in your input below the value that you
     * specify to typical limited range bounds. Enter an integer from 0 to 128. Leave blank to use the default value 64. The
     * value that you enter applies to 10-bit ranges. For 8-bit ranges, MediaConvert automatically scales this value down.
     * When you specify a value for Minumum YUV, you must set Sample range conversion to Limited range clip.
     *
     * @var int|null
     */
    private $minimumYuv;

    /**
     * @param array{
     *   MaximumRGBTolerance?: int|null,
     *   MaximumYUV?: int|null,
     *   MinimumRGBTolerance?: int|null,
     *   MinimumYUV?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maximumRgbTolerance = $input['MaximumRGBTolerance'] ?? null;
        $this->maximumYuv = $input['MaximumYUV'] ?? null;
        $this->minimumRgbTolerance = $input['MinimumRGBTolerance'] ?? null;
        $this->minimumYuv = $input['MinimumYUV'] ?? null;
    }

    /**
     * @param array{
     *   MaximumRGBTolerance?: int|null,
     *   MaximumYUV?: int|null,
     *   MinimumRGBTolerance?: int|null,
     *   MinimumYUV?: int|null,
     * }|ClipLimits $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaximumRgbTolerance(): ?int
    {
        return $this->maximumRgbTolerance;
    }

    public function getMaximumYuv(): ?int
    {
        return $this->maximumYuv;
    }

    public function getMinimumRgbTolerance(): ?int
    {
        return $this->minimumRgbTolerance;
    }

    public function getMinimumYuv(): ?int
    {
        return $this->minimumYuv;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maximumRgbTolerance) {
            $payload['maximumRGBTolerance'] = $v;
        }
        if (null !== $v = $this->maximumYuv) {
            $payload['maximumYUV'] = $v;
        }
        if (null !== $v = $this->minimumRgbTolerance) {
            $payload['minimumRGBTolerance'] = $v;
        }
        if (null !== $v = $this->minimumYuv) {
            $payload['minimumYUV'] = $v;
        }

        return $payload;
    }
}
