<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\ColorSpaceConversion;
use AsyncAws\MediaConvert\Enum\HDRToSDRToneMapper;
use AsyncAws\MediaConvert\Enum\SampleRangeConversion;

/**
 * Settings for color correction.
 */
final class ColorCorrector
{
    /**
     * Brightness level.
     *
     * @var int|null
     */
    private $brightness;

    /**
     * Specify YUV limits and RGB tolerances when you set Sample range conversion to Limited range clip.
     *
     * @var ClipLimits|null
     */
    private $clipLimits;

    /**
     * Specify the color space you want for this output. The service supports conversion between HDR formats, between SDR
     * formats, from SDR to HDR, and from HDR to SDR. SDR to HDR conversion doesn't upgrade the dynamic range. The converted
     * video has an HDR format, but visually appears the same as an unconverted output. HDR to SDR conversion uses tone
     * mapping to approximate the outcome of manually regrading from HDR to SDR. When you specify an output color space,
     * MediaConvert uses the following color space metadata, which includes color primaries, transfer characteristics, and
     * matrix coefficients:
     * * HDR 10: BT.2020, PQ, BT.2020 non-constant
     * * HLG 2020: BT.2020, HLG, BT.2020 non-constant
     * * P3DCI (Theater): DCIP3, SMPTE 428M, BT.709
     * * P3D65 (SDR): Display P3, sRGB, BT.709
     * * P3D65 (HDR): Display P3, PQ, BT.709.
     *
     * @var ColorSpaceConversion::*|null
     */
    private $colorSpaceConversion;

    /**
     * Contrast level.
     *
     * @var int|null
     */
    private $contrast;

    /**
     * Use these settings when you convert to the HDR 10 color space. Specify the SMPTE ST 2086 Mastering Display Color
     * Volume static metadata that you want signaled in the output. These values don't affect the pixel values that are
     * encoded in the video stream. They are intended to help the downstream video player display content in a way that
     * reflects the intentions of the the content creator. When you set Color space conversion to HDR 10, these settings are
     * required. You must set values for Max frame average light level and Max content light level; these settings don't
     * have a default value. The default values for the other HDR 10 metadata settings are defined by the P3D65 color space.
     * For more information about MediaConvert HDR jobs, see https://docs.aws.amazon.com/console/mediaconvert/hdr.
     *
     * @var Hdr10Metadata|null
     */
    private $hdr10Metadata;

    /**
     * Specify how MediaConvert maps brightness and colors from your HDR input to your SDR output. The mode that you select
     * represents a creative choice, with different tradeoffs in the details and tones of your output. To maintain details
     * in bright or saturated areas of your output: Choose Preserve details. For some sources, your SDR output may look less
     * bright and less saturated when compared to your HDR source. MediaConvert automatically applies this mode for HLG
     * sources, regardless of your choice. For a bright and saturated output: Choose Vibrant. We recommend that you choose
     * this mode when any of your source content is HDR10, and for the best results when it is mastered for 1000 nits. You
     * may notice loss of details in bright or saturated areas of your output. HDR to SDR tone mapping has no effect when
     * your input is SDR.
     *
     * @var HDRToSDRToneMapper::*|null
     */
    private $hdrToSdrToneMapper;

    /**
     * Hue in degrees.
     *
     * @var int|null
     */
    private $hue;

    /**
     * Specify the maximum mastering display luminance. Enter an integer from 0 to 2147483647, in units of 0.0001 nits. For
     * example, enter 10000000 for 1000 nits.
     *
     * @var int|null
     */
    private $maxLuminance;

    /**
     * Specify how MediaConvert limits the color sample range for this output. To create a limited range output from a full
     * range input: Choose Limited range squeeze. For full range inputs, MediaConvert performs a linear offset to color
     * samples equally across all pixels and frames. Color samples in 10-bit outputs are limited to 64 through 940, and
     * 8-bit outputs are limited to 16 through 235. Note: For limited range inputs, values for color samples are passed
     * through to your output unchanged. MediaConvert does not limit the sample range. To correct pixels in your input that
     * are out of range or out of gamut: Choose Limited range clip. Use for broadcast applications. MediaConvert conforms
     * any pixels outside of the values that you specify under Minimum YUV and Maximum YUV to limited range bounds.
     * MediaConvert also corrects any YUV values that, when converted to RGB, would be outside the bounds you specify under
     * Minimum RGB tolerance and Maximum RGB tolerance. With either limited range conversion, MediaConvert writes the sample
     * range metadata in the output.
     *
     * @var SampleRangeConversion::*|null
     */
    private $sampleRangeConversion;

    /**
     * Saturation level.
     *
     * @var int|null
     */
    private $saturation;

    /**
     * Specify the reference white level, in nits, for all of your SDR inputs. Use to correct brightness levels within HDR10
     * outputs. The following color metadata must be present in your SDR input: color primaries, transfer characteristics,
     * and matrix coefficients. If your SDR input has missing color metadata, or if you want to correct input color
     * metadata, manually specify a color space in the input video selector. For 1,000 nit peak brightness displays, we
     * recommend that you set SDR reference white level to 203 (according to ITU-R BT.2408). Leave blank to use the default
     * value of 100, or specify an integer from 100 to 1000.
     *
     * @var int|null
     */
    private $sdrReferenceWhiteLevel;

    /**
     * @param array{
     *   Brightness?: int|null,
     *   ClipLimits?: ClipLimits|array|null,
     *   ColorSpaceConversion?: ColorSpaceConversion::*|null,
     *   Contrast?: int|null,
     *   Hdr10Metadata?: Hdr10Metadata|array|null,
     *   HdrToSdrToneMapper?: HDRToSDRToneMapper::*|null,
     *   Hue?: int|null,
     *   MaxLuminance?: int|null,
     *   SampleRangeConversion?: SampleRangeConversion::*|null,
     *   Saturation?: int|null,
     *   SdrReferenceWhiteLevel?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->brightness = $input['Brightness'] ?? null;
        $this->clipLimits = isset($input['ClipLimits']) ? ClipLimits::create($input['ClipLimits']) : null;
        $this->colorSpaceConversion = $input['ColorSpaceConversion'] ?? null;
        $this->contrast = $input['Contrast'] ?? null;
        $this->hdr10Metadata = isset($input['Hdr10Metadata']) ? Hdr10Metadata::create($input['Hdr10Metadata']) : null;
        $this->hdrToSdrToneMapper = $input['HdrToSdrToneMapper'] ?? null;
        $this->hue = $input['Hue'] ?? null;
        $this->maxLuminance = $input['MaxLuminance'] ?? null;
        $this->sampleRangeConversion = $input['SampleRangeConversion'] ?? null;
        $this->saturation = $input['Saturation'] ?? null;
        $this->sdrReferenceWhiteLevel = $input['SdrReferenceWhiteLevel'] ?? null;
    }

    /**
     * @param array{
     *   Brightness?: int|null,
     *   ClipLimits?: ClipLimits|array|null,
     *   ColorSpaceConversion?: ColorSpaceConversion::*|null,
     *   Contrast?: int|null,
     *   Hdr10Metadata?: Hdr10Metadata|array|null,
     *   HdrToSdrToneMapper?: HDRToSDRToneMapper::*|null,
     *   Hue?: int|null,
     *   MaxLuminance?: int|null,
     *   SampleRangeConversion?: SampleRangeConversion::*|null,
     *   Saturation?: int|null,
     *   SdrReferenceWhiteLevel?: int|null,
     * }|ColorCorrector $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBrightness(): ?int
    {
        return $this->brightness;
    }

    public function getClipLimits(): ?ClipLimits
    {
        return $this->clipLimits;
    }

    /**
     * @return ColorSpaceConversion::*|null
     */
    public function getColorSpaceConversion(): ?string
    {
        return $this->colorSpaceConversion;
    }

    public function getContrast(): ?int
    {
        return $this->contrast;
    }

    public function getHdr10Metadata(): ?Hdr10Metadata
    {
        return $this->hdr10Metadata;
    }

    /**
     * @return HDRToSDRToneMapper::*|null
     */
    public function getHdrToSdrToneMapper(): ?string
    {
        return $this->hdrToSdrToneMapper;
    }

    public function getHue(): ?int
    {
        return $this->hue;
    }

    public function getMaxLuminance(): ?int
    {
        return $this->maxLuminance;
    }

    /**
     * @return SampleRangeConversion::*|null
     */
    public function getSampleRangeConversion(): ?string
    {
        return $this->sampleRangeConversion;
    }

    public function getSaturation(): ?int
    {
        return $this->saturation;
    }

    public function getSdrReferenceWhiteLevel(): ?int
    {
        return $this->sdrReferenceWhiteLevel;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->brightness) {
            $payload['brightness'] = $v;
        }
        if (null !== $v = $this->clipLimits) {
            $payload['clipLimits'] = $v->requestBody();
        }
        if (null !== $v = $this->colorSpaceConversion) {
            if (!ColorSpaceConversion::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "colorSpaceConversion" for "%s". The value "%s" is not a valid "ColorSpaceConversion".', __CLASS__, $v));
            }
            $payload['colorSpaceConversion'] = $v;
        }
        if (null !== $v = $this->contrast) {
            $payload['contrast'] = $v;
        }
        if (null !== $v = $this->hdr10Metadata) {
            $payload['hdr10Metadata'] = $v->requestBody();
        }
        if (null !== $v = $this->hdrToSdrToneMapper) {
            if (!HDRToSDRToneMapper::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "hdrToSdrToneMapper" for "%s". The value "%s" is not a valid "HDRToSDRToneMapper".', __CLASS__, $v));
            }
            $payload['hdrToSdrToneMapper'] = $v;
        }
        if (null !== $v = $this->hue) {
            $payload['hue'] = $v;
        }
        if (null !== $v = $this->maxLuminance) {
            $payload['maxLuminance'] = $v;
        }
        if (null !== $v = $this->sampleRangeConversion) {
            if (!SampleRangeConversion::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "sampleRangeConversion" for "%s". The value "%s" is not a valid "SampleRangeConversion".', __CLASS__, $v));
            }
            $payload['sampleRangeConversion'] = $v;
        }
        if (null !== $v = $this->saturation) {
            $payload['saturation'] = $v;
        }
        if (null !== $v = $this->sdrReferenceWhiteLevel) {
            $payload['sdrReferenceWhiteLevel'] = $v;
        }

        return $payload;
    }
}
