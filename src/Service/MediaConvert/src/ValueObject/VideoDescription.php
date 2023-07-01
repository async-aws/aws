<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AfdSignaling;
use AsyncAws\MediaConvert\Enum\AntiAlias;
use AsyncAws\MediaConvert\Enum\ColorMetadata;
use AsyncAws\MediaConvert\Enum\DropFrameTimecode;
use AsyncAws\MediaConvert\Enum\RespondToAfd;
use AsyncAws\MediaConvert\Enum\ScalingBehavior;
use AsyncAws\MediaConvert\Enum\VideoTimecodeInsertion;

/**
 * Settings related to video encoding of your output. The specific video settings depend on the video codec that you
 * choose. When you work directly in your JSON job specification, include one instance of Video description
 * (VideoDescription) per output.
 */
final class VideoDescription
{
    /**
     * This setting only applies to H.264, H.265, and MPEG2 outputs. Use Insert AFD signaling (AfdSignaling) to specify
     * whether the service includes AFD values in the output video data and what those values are. * Choose None to remove
     * all AFD values from this output. * Choose Fixed to ignore input AFD values and instead encode the value specified in
     * the job. * Choose Auto to calculate output AFD values based on the input AFD scaler data.
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
     * Video codec settings, (CodecSettings) under (VideoDescription), contains the group of settings related to video
     * encoding. The settings in this group vary depending on the value that you choose for Video codec (Codec). For each
     * codec enum that you choose, define the corresponding settings object. The following lists the codec enum, settings
     * object pairs. * AV1, Av1Settings * AVC_INTRA, AvcIntraSettings * FRAME_CAPTURE, FrameCaptureSettings * H_264,
     * H264Settings * H_265, H265Settings * MPEG2, Mpeg2Settings * PRORES, ProresSettings * VC3, Vc3Settings * VP8,
     * Vp8Settings * VP9, Vp9Settings * XAVC, XavcSettings.
     *
     * @var VideoCodecSettings|null
     */
    private $codecSettings;

    /**
     * Choose Insert (INSERT) for this setting to include color metadata in this output. Choose Ignore (IGNORE) to exclude
     * color metadata from this output. If you don't specify a value, the service sets this to Insert by default.
     *
     * @var ColorMetadata::*|null
     */
    private $colorMetadata;

    /**
     * Use Cropping selection (crop) to specify the video area that the service will include in the output video frame.
     *
     * @var Rectangle|null
     */
    private $crop;

    /**
     * Applies only to 29.97 fps outputs. When this feature is enabled, the service will use drop-frame timecode on outputs.
     * If it is not possible to use drop-frame timecode, the system will fall back to non-drop-frame. This setting is
     * enabled by default when Timecode insertion (TimecodeInsertion) is enabled.
     *
     * @var DropFrameTimecode::*|null
     */
    private $dropFrameTimecode;

    /**
     * Applies only if you set AFD Signaling(AfdSignaling) to Fixed (FIXED). Use Fixed (FixedAfd) to specify a four-bit AFD
     * value which the service will write on all frames of this video output.
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
     * Use Selection placement (position) to define the video area in your output frame. The area outside of the rectangle
     * that you specify here is black.
     *
     * @var Rectangle|null
     */
    private $position;

    /**
     * Use Respond to AFD (RespondToAfd) to specify how the service changes the video itself in response to AFD values in
     * the input. * Choose Respond to clip the input video frame according to the AFD value, input display aspect ratio, and
     * output display aspect ratio. * Choose Passthrough to include the input AFD values. Do not choose this when
     * AfdSignaling is set to (NONE). A preferred implementation of this workflow is to set RespondToAfd to (NONE) and set
     * AfdSignaling to (AUTO). * Choose None to remove all input AFD values from this output.
     *
     * @var RespondToAfd::*|null
     */
    private $respondToAfd;

    /**
     * Specify how the service handles outputs that have a different aspect ratio from the input aspect ratio. Choose
     * Stretch to output (STRETCH_TO_OUTPUT) to have the service stretch your video image to fit. Keep the setting Default
     * (DEFAULT) to have the service letterbox your video instead. This setting overrides any value that you specify for the
     * setting Selection placement (position) in this output.
     *
     * @var ScalingBehavior::*|null
     */
    private $scalingBehavior;

    /**
     * Use Sharpness (Sharpness) setting to specify the strength of anti-aliasing. This setting changes the width of the
     * anti-alias filter kernel used for scaling. Sharpness only applies if your output resolution is different from your
     * input resolution. 0 is the softest setting, 100 the sharpest, and 50 recommended for most content.
     *
     * @var int|null
     */
    private $sharpness;

    /**
     * Applies only to H.264, H.265, MPEG2, and ProRes outputs. Only enable Timecode insertion when the input frame rate is
     * identical to the output frame rate. To include timecodes in this output, set Timecode insertion
     * (VideoTimecodeInsertion) to PIC_TIMING_SEI. To leave them out, set it to DISABLED. Default is DISABLED. When the
     * service inserts timecodes in an output, by default, it uses any embedded timecodes from the input. If none are
     * present, the service will set the timecode for the first output frame to zero. To change this default behavior,
     * adjust the settings under Timecode configuration (TimecodeConfig). In the console, these settings are located under
     * Job > Job settings > Timecode configuration. Note - Timecode source under input settings (InputTimecodeSource) does
     * not affect the timecodes that are inserted in the output. Source under Job settings > Timecode configuration
     * (TimecodeSource) does.
     *
     * @var VideoTimecodeInsertion::*|null
     */
    private $timecodeInsertion;

    /**
     * Find additional transcoding features under Preprocessors (VideoPreprocessors). Enable the features at each output
     * individually. These features are disabled by default.
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
     *   AfdSignaling?: null|AfdSignaling::*,
     *   AntiAlias?: null|AntiAlias::*,
     *   CodecSettings?: null|VideoCodecSettings|array,
     *   ColorMetadata?: null|ColorMetadata::*,
     *   Crop?: null|Rectangle|array,
     *   DropFrameTimecode?: null|DropFrameTimecode::*,
     *   FixedAfd?: null|int,
     *   Height?: null|int,
     *   Position?: null|Rectangle|array,
     *   RespondToAfd?: null|RespondToAfd::*,
     *   ScalingBehavior?: null|ScalingBehavior::*,
     *   Sharpness?: null|int,
     *   TimecodeInsertion?: null|VideoTimecodeInsertion::*,
     *   VideoPreprocessors?: null|VideoPreprocessor|array,
     *   Width?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->afdSignaling = $input['AfdSignaling'] ?? null;
        $this->antiAlias = $input['AntiAlias'] ?? null;
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
        $this->videoPreprocessors = isset($input['VideoPreprocessors']) ? VideoPreprocessor::create($input['VideoPreprocessors']) : null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   AfdSignaling?: null|AfdSignaling::*,
     *   AntiAlias?: null|AntiAlias::*,
     *   CodecSettings?: null|VideoCodecSettings|array,
     *   ColorMetadata?: null|ColorMetadata::*,
     *   Crop?: null|Rectangle|array,
     *   DropFrameTimecode?: null|DropFrameTimecode::*,
     *   FixedAfd?: null|int,
     *   Height?: null|int,
     *   Position?: null|Rectangle|array,
     *   RespondToAfd?: null|RespondToAfd::*,
     *   ScalingBehavior?: null|ScalingBehavior::*,
     *   Sharpness?: null|int,
     *   TimecodeInsertion?: null|VideoTimecodeInsertion::*,
     *   VideoPreprocessors?: null|VideoPreprocessor|array,
     *   Width?: null|int,
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
                throw new InvalidArgument(sprintf('Invalid parameter "afdSignaling" for "%s". The value "%s" is not a valid "AfdSignaling".', __CLASS__, $v));
            }
            $payload['afdSignaling'] = $v;
        }
        if (null !== $v = $this->antiAlias) {
            if (!AntiAlias::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "antiAlias" for "%s". The value "%s" is not a valid "AntiAlias".', __CLASS__, $v));
            }
            $payload['antiAlias'] = $v;
        }
        if (null !== $v = $this->codecSettings) {
            $payload['codecSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->colorMetadata) {
            if (!ColorMetadata::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "colorMetadata" for "%s". The value "%s" is not a valid "ColorMetadata".', __CLASS__, $v));
            }
            $payload['colorMetadata'] = $v;
        }
        if (null !== $v = $this->crop) {
            $payload['crop'] = $v->requestBody();
        }
        if (null !== $v = $this->dropFrameTimecode) {
            if (!DropFrameTimecode::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "dropFrameTimecode" for "%s". The value "%s" is not a valid "DropFrameTimecode".', __CLASS__, $v));
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
                throw new InvalidArgument(sprintf('Invalid parameter "respondToAfd" for "%s". The value "%s" is not a valid "RespondToAfd".', __CLASS__, $v));
            }
            $payload['respondToAfd'] = $v;
        }
        if (null !== $v = $this->scalingBehavior) {
            if (!ScalingBehavior::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "scalingBehavior" for "%s". The value "%s" is not a valid "ScalingBehavior".', __CLASS__, $v));
            }
            $payload['scalingBehavior'] = $v;
        }
        if (null !== $v = $this->sharpness) {
            $payload['sharpness'] = $v;
        }
        if (null !== $v = $this->timecodeInsertion) {
            if (!VideoTimecodeInsertion::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "timecodeInsertion" for "%s". The value "%s" is not a valid "VideoTimecodeInsertion".', __CLASS__, $v));
            }
            $payload['timecodeInsertion'] = $v;
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
