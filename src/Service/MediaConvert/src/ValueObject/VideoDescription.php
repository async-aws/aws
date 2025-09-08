<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AfdSignaling;
use AsyncAws\MediaConvert\Enum\AntiAlias;
use AsyncAws\MediaConvert\Enum\ChromaPositionMode;
use AsyncAws\MediaConvert\Enum\ColorMetadata;
use AsyncAws\MediaConvert\Enum\DropFrameTimecode;
use AsyncAws\MediaConvert\Enum\RespondToAfd;
use AsyncAws\MediaConvert\Enum\ScalingBehavior;
use AsyncAws\MediaConvert\Enum\TimecodeTrack;
use AsyncAws\MediaConvert\Enum\VideoTimecodeInsertion;

/**
 * Settings related to video encoding of your output. The specific video settings depend on the video codec that you
 * choose.
 */
final class VideoDescription
{
    /**
     * This setting only applies to H.264, H.265, and MPEG2 outputs. Use Insert AFD signaling to specify whether the service
     * includes AFD values in the output video data and what those values are. * Choose None to remove all AFD values from
     * this output. * Choose Fixed to ignore input AFD values and instead encode the value specified in the job. * Choose
     * Auto to calculate output AFD values based on the input AFD scaler data.
     *
     * @var AfdSignaling::*|null
     */
    private $afdSignaling;

    /**
     * The anti-alias filter is automatically applied to all outputs. The service no longer accepts the value DISABLED for
     * AntiAlias. If you specify that in your job, the service will ignore the setting.
     *
     * @var AntiAlias::*|null
     */
    private $antiAlias;

    /**
     * Specify the chroma sample positioning metadata for your H.264 or H.265 output. To have MediaConvert automatically
     * determine chroma positioning: We recommend that you keep the default value, Auto. To specify center positioning:
     * Choose Force center. To specify top left positioning: Choose Force top left.
     *
     * @var ChromaPositionMode::*|null
     */
    private $chromaPositionMode;

    /**
     * Video codec settings contains the group of settings related to video encoding. The settings in this group vary
     * depending on the value that you choose for Video codec. For each codec enum that you choose, define the corresponding
     * settings object. The following lists the codec enum, settings object pairs. * AV1, Av1Settings * AVC_INTRA,
     * AvcIntraSettings * FRAME_CAPTURE, FrameCaptureSettings * GIF, GifSettings * H_264, H264Settings * H_265, H265Settings
     * * MPEG2, Mpeg2Settings * PRORES, ProresSettings * UNCOMPRESSED, UncompressedSettings * VC3, Vc3Settings * VP8,
     * Vp8Settings * VP9, Vp9Settings * XAVC, XavcSettings.
     *
     * @var VideoCodecSettings|null
     */
    private $codecSettings;

    /**
     * Choose Insert for this setting to include color metadata in this output. Choose Ignore to exclude color metadata from
     * this output. If you don't specify a value, the service sets this to Insert by default.
     *
     * @var ColorMetadata::*|null
     */
    private $colorMetadata;

    /**
     * Use Cropping selection to specify the video area that the service will include in the output video frame.
     *
     * @var Rectangle|null
     */
    private $crop;

    /**
     * Applies only to 29.97 fps outputs. When this feature is enabled, the service will use drop-frame timecode on outputs.
     * If it is not possible to use drop-frame timecode, the system will fall back to non-drop-frame. This setting is
     * enabled by default when Timecode insertion or Timecode track is enabled.
     *
     * @var DropFrameTimecode::*|null
     */
    private $dropFrameTimecode;

    /**
     * Applies only if you set AFD Signaling to Fixed. Use Fixed to specify a four-bit AFD value which the service will
     * write on all frames of this video output.
     *
     * @var int|null
     */
    private $fixedAfd;

    /**
     * Use Height to define the video resolution height, in pixels, for this output. To use the same resolution as your
     * input: Leave both Width and Height blank. To evenly scale from your input resolution: Leave Height blank and enter a
     * value for Width. For example, if your input is 1920x1080 and you set Width to 1280, your output will be 1280x720.
     *
     * @var int|null
     */
    private $height;

    /**
     * Use Selection placement to define the video area in your output frame. The area outside of the rectangle that you
     * specify here is black.
     *
     * @var Rectangle|null
     */
    private $position;

    /**
     * Use Respond to AFD to specify how the service changes the video itself in response to AFD values in the input. *
     * Choose Respond to clip the input video frame according to the AFD value, input display aspect ratio, and output
     * display aspect ratio. * Choose Passthrough to include the input AFD values. Do not choose this when AfdSignaling is
     * set to NONE. A preferred implementation of this workflow is to set RespondToAfd to and set AfdSignaling to AUTO. *
     * Choose None to remove all input AFD values from this output.
     *
     * @var RespondToAfd::*|null
     */
    private $respondToAfd;

    /**
     * Specify the video Scaling behavior when your output has a different resolution than your input. For more information,
     * see https://docs.aws.amazon.com/mediaconvert/latest/ug/video-scaling.html.
     *
     * @var ScalingBehavior::*|null
     */
    private $scalingBehavior;

    /**
     * Use Sharpness setting to specify the strength of anti-aliasing. This setting changes the width of the anti-alias
     * filter kernel used for scaling. Sharpness only applies if your output resolution is different from your input
     * resolution. 0 is the softest setting, 100 the sharpest, and 50 recommended for most content.
     *
     * @var int|null
     */
    private $sharpness;

    /**
     * Applies only to H.264, H.265, MPEG2, and ProRes outputs. Only enable Timecode insertion when the input frame rate is
     * identical to the output frame rate. To include timecodes in this output, set Timecode insertion to PIC_TIMING_SEI. To
     * leave them out, set it to DISABLED. Default is DISABLED. When the service inserts timecodes in an output, by default,
     * it uses any embedded timecodes from the input. If none are present, the service will set the timecode for the first
     * output frame to zero. To change this default behavior, adjust the settings under Timecode configuration. In the
     * console, these settings are located under Job > Job settings > Timecode configuration. Note - Timecode source under
     * input settings does not affect the timecodes that are inserted in the output. Source under Job settings > Timecode
     * configuration does.
     *
     * @var VideoTimecodeInsertion::*|null
     */
    private $timecodeInsertion;

    /**
     * To include a timecode track in your MP4 output: Choose Enabled. MediaConvert writes the timecode track in the Null
     * Media Header box (NMHD), without any timecode text formatting information. You can also specify dropframe or
     * non-dropframe timecode under the Drop Frame Timecode setting. To not include a timecode track: Keep the default
     * value, Disabled.
     *
     * @var TimecodeTrack::*|null
     */
    private $timecodeTrack;

    /**
     * Find additional transcoding features under Preprocessors. Enable the features at each output individually. These
     * features are disabled by default.
     *
     * @var VideoPreprocessor|null
     */
    private $videoPreprocessors;

    /**
     * Use Width to define the video resolution width, in pixels, for this output. To use the same resolution as your input:
     * Leave both Width and Height blank. To evenly scale from your input resolution: Leave Width blank and enter a value
     * for Height. For example, if your input is 1920x1080 and you set Height to 720, your output will be 1280x720.
     *
     * @var int|null
     */
    private $width;

    /**
     * @param array{
     *   AfdSignaling?: AfdSignaling::*|null,
     *   AntiAlias?: AntiAlias::*|null,
     *   ChromaPositionMode?: ChromaPositionMode::*|null,
     *   CodecSettings?: VideoCodecSettings|array|null,
     *   ColorMetadata?: ColorMetadata::*|null,
     *   Crop?: Rectangle|array|null,
     *   DropFrameTimecode?: DropFrameTimecode::*|null,
     *   FixedAfd?: int|null,
     *   Height?: int|null,
     *   Position?: Rectangle|array|null,
     *   RespondToAfd?: RespondToAfd::*|null,
     *   ScalingBehavior?: ScalingBehavior::*|null,
     *   Sharpness?: int|null,
     *   TimecodeInsertion?: VideoTimecodeInsertion::*|null,
     *   TimecodeTrack?: TimecodeTrack::*|null,
     *   VideoPreprocessors?: VideoPreprocessor|array|null,
     *   Width?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->afdSignaling = $input['AfdSignaling'] ?? null;
        $this->antiAlias = $input['AntiAlias'] ?? null;
        $this->chromaPositionMode = $input['ChromaPositionMode'] ?? null;
        $this->codecSettings = isset($input['CodecSettings']) ? VideoCodecSettings::create($input['CodecSettings']) : null;
        $this->colorMetadata = $input['ColorMetadata'] ?? null;
        $this->crop = isset($input['Crop']) ? Rectangle::create($input['Crop']) : null;
        $this->dropFrameTimecode = $input['DropFrameTimecode'] ?? null;
        $this->fixedAfd = $input['FixedAfd'] ?? null;
        $this->height = $input['Height'] ?? null;
        $this->position = isset($input['Position']) ? Rectangle::create($input['Position']) : null;
        $this->respondToAfd = $input['RespondToAfd'] ?? null;
        $this->scalingBehavior = $input['ScalingBehavior'] ?? null;
        $this->sharpness = $input['Sharpness'] ?? null;
        $this->timecodeInsertion = $input['TimecodeInsertion'] ?? null;
        $this->timecodeTrack = $input['TimecodeTrack'] ?? null;
        $this->videoPreprocessors = isset($input['VideoPreprocessors']) ? VideoPreprocessor::create($input['VideoPreprocessors']) : null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   AfdSignaling?: AfdSignaling::*|null,
     *   AntiAlias?: AntiAlias::*|null,
     *   ChromaPositionMode?: ChromaPositionMode::*|null,
     *   CodecSettings?: VideoCodecSettings|array|null,
     *   ColorMetadata?: ColorMetadata::*|null,
     *   Crop?: Rectangle|array|null,
     *   DropFrameTimecode?: DropFrameTimecode::*|null,
     *   FixedAfd?: int|null,
     *   Height?: int|null,
     *   Position?: Rectangle|array|null,
     *   RespondToAfd?: RespondToAfd::*|null,
     *   ScalingBehavior?: ScalingBehavior::*|null,
     *   Sharpness?: int|null,
     *   TimecodeInsertion?: VideoTimecodeInsertion::*|null,
     *   TimecodeTrack?: TimecodeTrack::*|null,
     *   VideoPreprocessors?: VideoPreprocessor|array|null,
     *   Width?: int|null,
     * }|VideoDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AfdSignaling::*|null
     */
    public function getAfdSignaling(): ?string
    {
        return $this->afdSignaling;
    }

    /**
     * @return AntiAlias::*|null
     */
    public function getAntiAlias(): ?string
    {
        return $this->antiAlias;
    }

    /**
     * @return ChromaPositionMode::*|null
     */
    public function getChromaPositionMode(): ?string
    {
        return $this->chromaPositionMode;
    }

    public function getCodecSettings(): ?VideoCodecSettings
    {
        return $this->codecSettings;
    }

    /**
     * @return ColorMetadata::*|null
     */
    public function getColorMetadata(): ?string
    {
        return $this->colorMetadata;
    }

    public function getCrop(): ?Rectangle
    {
        return $this->crop;
    }

    /**
     * @return DropFrameTimecode::*|null
     */
    public function getDropFrameTimecode(): ?string
    {
        return $this->dropFrameTimecode;
    }

    public function getFixedAfd(): ?int
    {
        return $this->fixedAfd;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getPosition(): ?Rectangle
    {
        return $this->position;
    }

    /**
     * @return RespondToAfd::*|null
     */
    public function getRespondToAfd(): ?string
    {
        return $this->respondToAfd;
    }

    /**
     * @return ScalingBehavior::*|null
     */
    public function getScalingBehavior(): ?string
    {
        return $this->scalingBehavior;
    }

    public function getSharpness(): ?int
    {
        return $this->sharpness;
    }

    /**
     * @return VideoTimecodeInsertion::*|null
     */
    public function getTimecodeInsertion(): ?string
    {
        return $this->timecodeInsertion;
    }

    /**
     * @return TimecodeTrack::*|null
     */
    public function getTimecodeTrack(): ?string
    {
        return $this->timecodeTrack;
    }

    public function getVideoPreprocessors(): ?VideoPreprocessor
    {
        return $this->videoPreprocessors;
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
        if (null !== $v = $this->afdSignaling) {
            if (!AfdSignaling::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "afdSignaling" for "%s". The value "%s" is not a valid "AfdSignaling".', __CLASS__, $v));
            }
            $payload['afdSignaling'] = $v;
        }
        if (null !== $v = $this->antiAlias) {
            if (!AntiAlias::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "antiAlias" for "%s". The value "%s" is not a valid "AntiAlias".', __CLASS__, $v));
            }
            $payload['antiAlias'] = $v;
        }
        if (null !== $v = $this->chromaPositionMode) {
            if (!ChromaPositionMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "chromaPositionMode" for "%s". The value "%s" is not a valid "ChromaPositionMode".', __CLASS__, $v));
            }
            $payload['chromaPositionMode'] = $v;
        }
        if (null !== $v = $this->codecSettings) {
            $payload['codecSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->colorMetadata) {
            if (!ColorMetadata::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "colorMetadata" for "%s". The value "%s" is not a valid "ColorMetadata".', __CLASS__, $v));
            }
            $payload['colorMetadata'] = $v;
        }
        if (null !== $v = $this->crop) {
            $payload['crop'] = $v->requestBody();
        }
        if (null !== $v = $this->dropFrameTimecode) {
            if (!DropFrameTimecode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "dropFrameTimecode" for "%s". The value "%s" is not a valid "DropFrameTimecode".', __CLASS__, $v));
            }
            $payload['dropFrameTimecode'] = $v;
        }
        if (null !== $v = $this->fixedAfd) {
            $payload['fixedAfd'] = $v;
        }
        if (null !== $v = $this->height) {
            $payload['height'] = $v;
        }
        if (null !== $v = $this->position) {
            $payload['position'] = $v->requestBody();
        }
        if (null !== $v = $this->respondToAfd) {
            if (!RespondToAfd::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "respondToAfd" for "%s". The value "%s" is not a valid "RespondToAfd".', __CLASS__, $v));
            }
            $payload['respondToAfd'] = $v;
        }
        if (null !== $v = $this->scalingBehavior) {
            if (!ScalingBehavior::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "scalingBehavior" for "%s". The value "%s" is not a valid "ScalingBehavior".', __CLASS__, $v));
            }
            $payload['scalingBehavior'] = $v;
        }
        if (null !== $v = $this->sharpness) {
            $payload['sharpness'] = $v;
        }
        if (null !== $v = $this->timecodeInsertion) {
            if (!VideoTimecodeInsertion::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timecodeInsertion" for "%s". The value "%s" is not a valid "VideoTimecodeInsertion".', __CLASS__, $v));
            }
            $payload['timecodeInsertion'] = $v;
        }
        if (null !== $v = $this->timecodeTrack) {
            if (!TimecodeTrack::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timecodeTrack" for "%s". The value "%s" is not a valid "TimecodeTrack".', __CLASS__, $v));
            }
            $payload['timecodeTrack'] = $v;
        }
        if (null !== $v = $this->videoPreprocessors) {
            $payload['videoPreprocessors'] = $v->requestBody();
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
        }

        return $payload;
    }
}
