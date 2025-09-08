<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use these settings to specify static color calibration metadata, as defined by SMPTE ST 2086. These values don't
 * affect the pixel values that are encoded in the video stream. They are intended to help the downstream video player
 * display content in a way that reflects the intentions of the the content creator.
 */
final class Hdr10Metadata
{
    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $bluePrimaryX;

    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $bluePrimaryY;

    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $greenPrimaryX;

    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $greenPrimaryY;

    /**
     * Maximum light level among all samples in the coded video sequence, in units of candelas per square meter. This
     * setting doesn't have a default value; you must specify a value that is suitable for the content.
     *
     * @var int|null
     */
    private $maxContentLightLevel;

    /**
     * Maximum average light level of any frame in the coded video sequence, in units of candelas per square meter. This
     * setting doesn't have a default value; you must specify a value that is suitable for the content.
     *
     * @var int|null
     */
    private $maxFrameAverageLightLevel;

    /**
     * Nominal maximum mastering display luminance in units of of 0.0001 candelas per square meter.
     *
     * @var int|null
     */
    private $maxLuminance;

    /**
     * Nominal minimum mastering display luminance in units of of 0.0001 candelas per square meter.
     *
     * @var int|null
     */
    private $minLuminance;

    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $redPrimaryX;

    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $redPrimaryY;

    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $whitePointX;

    /**
     * HDR Master Display Information must be provided by a color grader, using color grading tools. Range is 0 to 50,000,
     * each increment represents 0.00002 in CIE1931 color coordinate. Note that this setting is not for color correction.
     *
     * @var int|null
     */
    private $whitePointY;

    /**
     * @param array{
     *   BluePrimaryX?: int|null,
     *   BluePrimaryY?: int|null,
     *   GreenPrimaryX?: int|null,
     *   GreenPrimaryY?: int|null,
     *   MaxContentLightLevel?: int|null,
     *   MaxFrameAverageLightLevel?: int|null,
     *   MaxLuminance?: int|null,
     *   MinLuminance?: int|null,
     *   RedPrimaryX?: int|null,
     *   RedPrimaryY?: int|null,
     *   WhitePointX?: int|null,
     *   WhitePointY?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bluePrimaryX = $input['BluePrimaryX'] ?? null;
        $this->bluePrimaryY = $input['BluePrimaryY'] ?? null;
        $this->greenPrimaryX = $input['GreenPrimaryX'] ?? null;
        $this->greenPrimaryY = $input['GreenPrimaryY'] ?? null;
        $this->maxContentLightLevel = $input['MaxContentLightLevel'] ?? null;
        $this->maxFrameAverageLightLevel = $input['MaxFrameAverageLightLevel'] ?? null;
        $this->maxLuminance = $input['MaxLuminance'] ?? null;
        $this->minLuminance = $input['MinLuminance'] ?? null;
        $this->redPrimaryX = $input['RedPrimaryX'] ?? null;
        $this->redPrimaryY = $input['RedPrimaryY'] ?? null;
        $this->whitePointX = $input['WhitePointX'] ?? null;
        $this->whitePointY = $input['WhitePointY'] ?? null;
    }

    /**
     * @param array{
     *   BluePrimaryX?: int|null,
     *   BluePrimaryY?: int|null,
     *   GreenPrimaryX?: int|null,
     *   GreenPrimaryY?: int|null,
     *   MaxContentLightLevel?: int|null,
     *   MaxFrameAverageLightLevel?: int|null,
     *   MaxLuminance?: int|null,
     *   MinLuminance?: int|null,
     *   RedPrimaryX?: int|null,
     *   RedPrimaryY?: int|null,
     *   WhitePointX?: int|null,
     *   WhitePointY?: int|null,
     * }|Hdr10Metadata $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBluePrimaryX(): ?int
    {
        return $this->bluePrimaryX;
    }

    public function getBluePrimaryY(): ?int
    {
        return $this->bluePrimaryY;
    }

    public function getGreenPrimaryX(): ?int
    {
        return $this->greenPrimaryX;
    }

    public function getGreenPrimaryY(): ?int
    {
        return $this->greenPrimaryY;
    }

    public function getMaxContentLightLevel(): ?int
    {
        return $this->maxContentLightLevel;
    }

    public function getMaxFrameAverageLightLevel(): ?int
    {
        return $this->maxFrameAverageLightLevel;
    }

    public function getMaxLuminance(): ?int
    {
        return $this->maxLuminance;
    }

    public function getMinLuminance(): ?int
    {
        return $this->minLuminance;
    }

    public function getRedPrimaryX(): ?int
    {
        return $this->redPrimaryX;
    }

    public function getRedPrimaryY(): ?int
    {
        return $this->redPrimaryY;
    }

    public function getWhitePointX(): ?int
    {
        return $this->whitePointX;
    }

    public function getWhitePointY(): ?int
    {
        return $this->whitePointY;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bluePrimaryX) {
            $payload['bluePrimaryX'] = $v;
        }
        if (null !== $v = $this->bluePrimaryY) {
            $payload['bluePrimaryY'] = $v;
        }
        if (null !== $v = $this->greenPrimaryX) {
            $payload['greenPrimaryX'] = $v;
        }
        if (null !== $v = $this->greenPrimaryY) {
            $payload['greenPrimaryY'] = $v;
        }
        if (null !== $v = $this->maxContentLightLevel) {
            $payload['maxContentLightLevel'] = $v;
        }
        if (null !== $v = $this->maxFrameAverageLightLevel) {
            $payload['maxFrameAverageLightLevel'] = $v;
        }
        if (null !== $v = $this->maxLuminance) {
            $payload['maxLuminance'] = $v;
        }
        if (null !== $v = $this->minLuminance) {
            $payload['minLuminance'] = $v;
        }
        if (null !== $v = $this->redPrimaryX) {
            $payload['redPrimaryX'] = $v;
        }
        if (null !== $v = $this->redPrimaryY) {
            $payload['redPrimaryY'] = $v;
        }
        if (null !== $v = $this->whitePointX) {
            $payload['whitePointX'] = $v;
        }
        if (null !== $v = $this->whitePointY) {
            $payload['whitePointY'] = $v;
        }

        return $payload;
    }
}
