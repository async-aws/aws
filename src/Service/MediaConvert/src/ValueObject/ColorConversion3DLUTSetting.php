<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\ColorSpace;

/**
 * Custom 3D lut settings.
 */
final class ColorConversion3DLUTSetting
{
    /**
     * Specify the input file S3, HTTP, or HTTPS URL for your 3D LUT .cube file. Note that MediaConvert accepts 3D LUT files
     * up to 8MB in size.
     *
     * @var string|null
     */
    private $fileInput;

    /**
     * Specify which inputs use this 3D LUT, according to their color space.
     *
     * @var ColorSpace::*|string|null
     */
    private $inputColorSpace;

    /**
     * Specify which inputs use this 3D LUT, according to their luminance. To apply this 3D LUT to HDR10 or P3D65 (HDR)
     * inputs with a specific mastering luminance: Enter an integer from 0 to 2147483647, corresponding to the input's
     * Maximum luminance value. To apply this 3D LUT to any input regardless of its luminance: Leave blank, or enter 0.
     *
     * @var int|null
     */
    private $inputMasteringLuminance;

    /**
     * Specify which outputs use this 3D LUT, according to their color space.
     *
     * @var ColorSpace::*|string|null
     */
    private $outputColorSpace;

    /**
     * Specify which outputs use this 3D LUT, according to their luminance. To apply this 3D LUT to HDR10 or P3D65 (HDR)
     * outputs with a specific luminance: Enter an integer from 0 to 2147483647, corresponding to the output's luminance. To
     * apply this 3D LUT to any output regardless of its luminance: Leave blank, or enter 0.
     *
     * @var int|null
     */
    private $outputMasteringLuminance;

    /**
     * @param array{
     *   FileInput?: null|string,
     *   InputColorSpace?: null|ColorSpace::*|string,
     *   InputMasteringLuminance?: null|int,
     *   OutputColorSpace?: null|ColorSpace::*|string,
     *   OutputMasteringLuminance?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fileInput = $input['FileInput'] ?? null;
        $this->inputColorSpace = $input['InputColorSpace'] ?? null;
        $this->inputMasteringLuminance = $input['InputMasteringLuminance'] ?? null;
        $this->outputColorSpace = $input['OutputColorSpace'] ?? null;
        $this->outputMasteringLuminance = $input['OutputMasteringLuminance'] ?? null;
    }

    /**
     * @param array{
     *   FileInput?: null|string,
     *   InputColorSpace?: null|ColorSpace::*|string,
     *   InputMasteringLuminance?: null|int,
     *   OutputColorSpace?: null|ColorSpace::*|string,
     *   OutputMasteringLuminance?: null|int,
     * }|ColorConversion3DLUTSetting $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFileInput(): ?string
    {
        return $this->fileInput;
    }

    /**
     * @return ColorSpace::*|string|null
     */
    public function getInputColorSpace(): ?string
    {
        return $this->inputColorSpace;
    }

    public function getInputMasteringLuminance(): ?int
    {
        return $this->inputMasteringLuminance;
    }

    /**
     * @return ColorSpace::*|string|null
     */
    public function getOutputColorSpace(): ?string
    {
        return $this->outputColorSpace;
    }

    public function getOutputMasteringLuminance(): ?int
    {
        return $this->outputMasteringLuminance;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->fileInput) {
            $payload['fileInput'] = $v;
        }
        if (null !== $v = $this->inputColorSpace) {
            if (!ColorSpace::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "inputColorSpace" for "%s". The value "%s" is not a valid "ColorSpace".', __CLASS__, $v));
            }
            $payload['inputColorSpace'] = $v;
        }
        if (null !== $v = $this->inputMasteringLuminance) {
            $payload['inputMasteringLuminance'] = $v;
        }
        if (null !== $v = $this->outputColorSpace) {
            if (!ColorSpace::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "outputColorSpace" for "%s". The value "%s" is not a valid "ColorSpace".', __CLASS__, $v));
            }
            $payload['outputColorSpace'] = $v;
        }
        if (null !== $v = $this->outputMasteringLuminance) {
            $payload['outputMasteringLuminance'] = $v;
        }

        return $payload;
    }
}
