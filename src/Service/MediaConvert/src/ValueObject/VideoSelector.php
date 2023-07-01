<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AlphaBehavior;
use AsyncAws\MediaConvert\Enum\ColorSpace;
use AsyncAws\MediaConvert\Enum\ColorSpaceUsage;
use AsyncAws\MediaConvert\Enum\EmbeddedTimecodeOverride;
use AsyncAws\MediaConvert\Enum\InputRotate;
use AsyncAws\MediaConvert\Enum\InputSampleRange;
use AsyncAws\MediaConvert\Enum\PadVideo;

/**
 * Input video selectors contain the video settings for the input. Each of your inputs can have up to one video
 * selector.
 */
final class VideoSelector
{
    /**
     * Ignore this setting unless this input is a QuickTime animation with an alpha channel. Use this setting to create
     * separate Key and Fill outputs. In each output, specify which part of the input MediaConvert uses. Leave this setting
     * at the default value DISCARD to delete the alpha channel and preserve the video. Set it to REMAP_TO_LUMA to delete
     * the video and map the alpha channel to the luma channel of your outputs.
     *
     * @var AlphaBehavior::*|null
     */
    private $alphaBehavior;

    /**
     * If your input video has accurate color space metadata, or if you don't know about color space: Keep the default
     * value, Follow. MediaConvert will automatically detect your input color space. If your input video has metadata
     * indicating the wrong color space, or has missing metadata: Specify the accurate color space here. If your input video
     * is HDR 10 and the SMPTE ST 2086 Mastering Display Color Volume static metadata isn't present in your video stream, or
     * if that metadata is present but not accurate: Choose Force HDR 10. Specify correct values in the input HDR 10
     * metadata settings. For more information about HDR jobs, see https://docs.aws.amazon.com/console/mediaconvert/hdr.
     * When you specify an input color space, MediaConvert uses the following color space metadata, which includes color
     * primaries, transfer characteristics, and matrix coefficients:
     * * HDR 10: BT.2020, PQ, BT.2020 non-constant
     * * HLG 2020: BT.2020, HLG, BT.2020 non-constant
     * * P3DCI (Theater): DCIP3, SMPTE 428M, BT.709
     * * P3D65 (SDR): Display P3, sRGB, BT.709
     * * P3D65 (HDR): Display P3, PQ, BT.709.
     *
     * @var ColorSpace::*|null
     */
    private $colorSpace;

    /**
     * There are two sources for color metadata, the input file and the job input settings Color space (ColorSpace) and HDR
     * master display information settings(Hdr10Metadata). The Color space usage setting determines which takes precedence.
     * Choose Force (FORCE) to use color metadata from the input job settings. If you don't specify values for those
     * settings, the service defaults to using metadata from your input. FALLBACK - Choose Fallback (FALLBACK) to use color
     * metadata from the source when it is present. If there's no color metadata in your input file, the service defaults to
     * using values you specify in the input settings.
     *
     * @var ColorSpaceUsage::*|null
     */
    private $colorSpaceUsage;

    /**
     * Set Embedded timecode override (embeddedTimecodeOverride) to Use MDPM (USE_MDPM) when your AVCHD input contains
     * timecode tag data in the Modified Digital Video Pack Metadata (MDPM). When you do, we recommend you also set Timecode
     * source (inputTimecodeSource) to Embedded (EMBEDDED). Leave Embedded timecode override blank, or set to None (NONE),
     * when your input does not contain MDPM timecode.
     *
     * @var EmbeddedTimecodeOverride::*|null
     */
    private $embeddedTimecodeOverride;

    /**
     * Use these settings to provide HDR 10 metadata that is missing or inaccurate in your input video. Appropriate values
     * vary depending on the input video and must be provided by a color grader. The color grader generates these values
     * during the HDR 10 mastering process. The valid range for each of these settings is 0 to 50,000. Each increment
     * represents 0.00002 in CIE1931 color coordinate. Related settings - When you specify these values, you must also set
     * Color space (ColorSpace) to HDR 10 (HDR10). To specify whether the the values you specify here take precedence over
     * the values in the metadata of your input file, set Color space usage (ColorSpaceUsage). To specify whether color
     * metadata is included in an output, set Color metadata (ColorMetadata). For more information about MediaConvert HDR
     * jobs, see https://docs.aws.amazon.com/console/mediaconvert/hdr.
     *
     * @var Hdr10Metadata|null
     */
    private $hdr10Metadata;

    /**
     * Use this setting if your input has video and audio durations that don't align, and your output or player has strict
     * alignment requirements. Examples: Input audio track has a delayed start. Input video track ends before audio ends.
     * When you set Pad video (padVideo) to Black (BLACK), MediaConvert generates black video frames so that output video
     * and audio durations match. Black video frames are added at the beginning or end, depending on your input. To keep the
     * default behavior and not generate black video, set Pad video to Disabled (DISABLED) or leave blank.
     *
     * @var PadVideo::*|null
     */
    private $padVideo;

    /**
     * Use PID (Pid) to select specific video data from an input file. Specify this value as an integer; the system
     * automatically converts it to the hexidecimal value. For example, 257 selects PID 0x101. A PID, or packet identifier,
     * is an identifier for a set of data in an MPEG-2 transport stream container.
     *
     * @var int|null
     */
    private $pid;

    /**
     * Selects a specific program from within a multi-program transport stream. Note that Quad 4K is not currently
     * supported.
     *
     * @var int|null
     */
    private $programNumber;

    /**
     * Use Rotate (InputRotate) to specify how the service rotates your video. You can choose automatic rotation or specify
     * a rotation. You can specify a clockwise rotation of 0, 90, 180, or 270 degrees. If your input video container is .mov
     * or .mp4 and your input has rotation metadata, you can choose Automatic to have the service rotate your video
     * according to the rotation specified in the metadata. The rotation must be within one degree of 90, 180, or 270
     * degrees. If the rotation metadata specifies any other rotation, the service will default to no rotation. By default,
     * the service does no rotation, even if your input video has rotation metadata. The service doesn't pass through
     * rotation metadata.
     *
     * @var InputRotate::*|null
     */
    private $rotate;

    /**
     * If the sample range metadata in your input video is accurate, or if you don't know about sample range, keep the
     * default value, Follow (FOLLOW), for this setting. When you do, the service automatically detects your input sample
     * range. If your input video has metadata indicating the wrong sample range, specify the accurate sample range here.
     * When you do, MediaConvert ignores any sample range information in the input metadata. Regardless of whether
     * MediaConvert uses the input sample range or the sample range that you specify, MediaConvert uses the sample range for
     * transcoding and also writes it to the output metadata.
     *
     * @var InputSampleRange::*|null
     */
    private $sampleRange;

    /**
     * @param array{
     *   AlphaBehavior?: null|AlphaBehavior::*,
     *   ColorSpace?: null|ColorSpace::*,
     *   ColorSpaceUsage?: null|ColorSpaceUsage::*,
     *   EmbeddedTimecodeOverride?: null|EmbeddedTimecodeOverride::*,
     *   Hdr10Metadata?: null|Hdr10Metadata|array,
     *   PadVideo?: null|PadVideo::*,
     *   Pid?: null|int,
     *   ProgramNumber?: null|int,
     *   Rotate?: null|InputRotate::*,
     *   SampleRange?: null|InputSampleRange::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->alphaBehavior = $input['AlphaBehavior'] ?? null;
        $this->colorSpace = $input['ColorSpace'] ?? null;
        $this->colorSpaceUsage = $input['ColorSpaceUsage'] ?? null;
        $this->embeddedTimecodeOverride = $input['EmbeddedTimecodeOverride'] ?? null;
        $this->hdr10Metadata = isset($input['Hdr10Metadata']) ? Hdr10Metadata::create($input['Hdr10Metadata']) : null;
        $this->padVideo = $input['PadVideo'] ?? null;
        $this->pid = $input['Pid'] ?? null;
        $this->programNumber = $input['ProgramNumber'] ?? null;
        $this->rotate = $input['Rotate'] ?? null;
        $this->sampleRange = $input['SampleRange'] ?? null;
    }

    /**
     * @param array{
     *   AlphaBehavior?: null|AlphaBehavior::*,
     *   ColorSpace?: null|ColorSpace::*,
     *   ColorSpaceUsage?: null|ColorSpaceUsage::*,
     *   EmbeddedTimecodeOverride?: null|EmbeddedTimecodeOverride::*,
     *   Hdr10Metadata?: null|Hdr10Metadata|array,
     *   PadVideo?: null|PadVideo::*,
     *   Pid?: null|int,
     *   ProgramNumber?: null|int,
     *   Rotate?: null|InputRotate::*,
     *   SampleRange?: null|InputSampleRange::*,
     * }|VideoSelector $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AlphaBehavior::*|null
     */
    public function getAlphaBehavior(): ?string
    {
        return $this->alphaBehavior;
    }

    /**
     * @return ColorSpace::*|null
     */
    public function getColorSpace(): ?string
    {
        return $this->colorSpace;
    }

    /**
     * @return ColorSpaceUsage::*|null
     */
    public function getColorSpaceUsage(): ?string
    {
        return $this->colorSpaceUsage;
    }

    /**
     * @return EmbeddedTimecodeOverride::*|null
     */
    public function getEmbeddedTimecodeOverride(): ?string
    {
        return $this->embeddedTimecodeOverride;
    }

    public function getHdr10Metadata(): ?Hdr10Metadata
    {
        return $this->hdr10Metadata;
    }

    /**
     * @return PadVideo::*|null
     */
    public function getPadVideo(): ?string
    {
        return $this->padVideo;
    }

    public function getPid(): ?int
    {
        return $this->pid;
    }

    public function getProgramNumber(): ?int
    {
        return $this->programNumber;
    }

    /**
     * @return InputRotate::*|null
     */
    public function getRotate(): ?string
    {
        return $this->rotate;
    }

    /**
     * @return InputSampleRange::*|null
     */
    public function getSampleRange(): ?string
    {
        return $this->sampleRange;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->alphaBehavior) {
            if (!AlphaBehavior::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "alphaBehavior" for "%s". The value "%s" is not a valid "AlphaBehavior".', __CLASS__, $v));
            }
            $payload['alphaBehavior'] = $v;
        }
        if (null !== $v = $this->colorSpace) {
            if (!ColorSpace::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "colorSpace" for "%s". The value "%s" is not a valid "ColorSpace".', __CLASS__, $v));
            }
            $payload['colorSpace'] = $v;
        }
        if (null !== $v = $this->colorSpaceUsage) {
            if (!ColorSpaceUsage::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "colorSpaceUsage" for "%s". The value "%s" is not a valid "ColorSpaceUsage".', __CLASS__, $v));
            }
            $payload['colorSpaceUsage'] = $v;
        }
        if (null !== $v = $this->embeddedTimecodeOverride) {
            if (!EmbeddedTimecodeOverride::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "embeddedTimecodeOverride" for "%s". The value "%s" is not a valid "EmbeddedTimecodeOverride".', __CLASS__, $v));
            }
            $payload['embeddedTimecodeOverride'] = $v;
        }
        if (null !== $v = $this->hdr10Metadata) {
            $payload['hdr10Metadata'] = $v->requestBody();
        }
        if (null !== $v = $this->padVideo) {
            if (!PadVideo::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "padVideo" for "%s". The value "%s" is not a valid "PadVideo".', __CLASS__, $v));
            }
            $payload['padVideo'] = $v;
        }
        if (null !== $v = $this->pid) {
            $payload['pid'] = $v;
        }
        if (null !== $v = $this->programNumber) {
            $payload['programNumber'] = $v;
        }
        if (null !== $v = $this->rotate) {
            if (!InputRotate::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "rotate" for "%s". The value "%s" is not a valid "InputRotate".', __CLASS__, $v));
            }
            $payload['rotate'] = $v;
        }
        if (null !== $v = $this->sampleRange) {
            if (!InputSampleRange::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "sampleRange" for "%s". The value "%s" is not a valid "InputSampleRange".', __CLASS__, $v));
            }
            $payload['sampleRange'] = $v;
        }

        return $payload;
    }
}
