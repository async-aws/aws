<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * JobSettings contains all the transcode settings for a job.
 */
final class JobSettings
{
    /**
     * When specified, this offset (in milliseconds) is added to the input Ad Avail PTS time.
     *
     * @var int|null
     */
    private $adAvailOffset;

    /**
     * Settings for ad avail blanking. Video can be blanked or overlaid with an image, and audio muted during SCTE-35
     * triggered ad avails.
     *
     * @var AvailBlanking|null
     */
    private $availBlanking;

    /**
     * Use 3D LUTs to specify custom color mapping behavior when you convert from one color space into another. You can
     * include up to 8 different 3D LUTs. For more information, see:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/3d-luts.html.
     *
     * @var ColorConversion3DLUTSetting[]|null
     */
    private $colorConversion3DlutSettings;

    /**
     * Settings for Event Signaling And Messaging (ESAM). If you don't do ad insertion, you can ignore these settings.
     *
     * @var EsamSettings|null
     */
    private $esam;

    /**
     * If your source content has EIA-608 Line 21 Data Services, enable this feature to specify what MediaConvert does with
     * the Extended Data Services (XDS) packets. You can choose to pass through XDS packets, or remove them from the output.
     * For more information about XDS, see EIA-608 Line Data Services, section 9.5.1.5 05h Content Advisory.
     *
     * @var ExtendedDataServices|null
     */
    private $extendedDataServices;

    /**
     * Specify the input that MediaConvert references for your default output settings. MediaConvert uses this input's
     * Resolution, Frame rate, and Pixel aspect ratio for all outputs that you don't manually specify different output
     * settings for. Enabling this setting will disable "Follow source" for all other inputs. If MediaConvert cannot follow
     * your source, for example if you specify an audio-only input, MediaConvert uses the first followable input instead. In
     * your JSON job specification, enter an integer from 1 to 150 corresponding to the order of your inputs.
     *
     * @var int|null
     */
    private $followSource;

    /**
     * Use Inputs to define source file used in the transcode job. There can be multiple inputs add in a job. These inputs
     * will be concantenated together to create the output.
     *
     * @var Input[]|null
     */
    private $inputs;

    /**
     * Use these settings only when you use Kantar watermarking. Specify the values that MediaConvert uses to generate and
     * place Kantar watermarks in your output audio. These settings apply to every output in your job. In addition to
     * specifying these values, you also need to store your Kantar credentials in AWS Secrets Manager. For more information,
     * see https://docs.aws.amazon.com/mediaconvert/latest/ug/kantar-watermarking.html.
     *
     * @var KantarWatermarkSettings|null
     */
    private $kantarWatermark;

    /**
     * Overlay motion graphics on top of your video. The motion graphics that you specify here appear on all outputs in all
     * output groups. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/motion-graphic-overlay.html.
     *
     * @var MotionImageInserter|null
     */
    private $motionImageInserter;

    /**
     * Settings for your Nielsen configuration. If you don't do Nielsen measurement and analytics, ignore these settings.
     * When you enable Nielsen configuration, MediaConvert enables PCM to ID3 tagging for all outputs in the job.
     *
     * @var NielsenConfiguration|null
     */
    private $nielsenConfiguration;

    /**
     * Ignore these settings unless you are using Nielsen non-linear watermarking. Specify the values that MediaConvert uses
     * to generate and place Nielsen watermarks in your output audio. In addition to specifying these values, you also need
     * to set up your cloud TIC server. These settings apply to every output in your job. The MediaConvert implementation is
     * currently with the following Nielsen versions: Nielsen Watermark SDK Version 6.0.13 Nielsen NLM Watermark Engine
     * Version 1.3.3 Nielsen Watermark Authenticator [SID_TIC] Version [7.0.0].
     *
     * @var NielsenNonLinearWatermarkSettings|null
     */
    private $nielsenNonLinearWatermark;

    /**
     * Contains one group of settings for each set of outputs that share a common package type. All unpackaged files
     * (MPEG-4, MPEG-2 TS, Quicktime, MXF, and no container) are grouped in a single output group as well. Required in is a
     * group of settings that apply to the whole group. This required object depends on the value you set for Type. Type,
     * settings object pairs are as follows. * FILE_GROUP_SETTINGS, FileGroupSettings * HLS_GROUP_SETTINGS, HlsGroupSettings
     * * DASH_ISO_GROUP_SETTINGS, DashIsoGroupSettings * MS_SMOOTH_GROUP_SETTINGS, MsSmoothGroupSettings *
     * CMAF_GROUP_SETTINGS, CmafGroupSettings.
     *
     * @var OutputGroup[]|null
     */
    private $outputGroups;

    /**
     * These settings control how the service handles timecodes throughout the job. These settings don't affect input
     * clipping.
     *
     * @var TimecodeConfig|null
     */
    private $timecodeConfig;

    /**
     * Insert user-defined custom ID3 metadata at timecodes that you specify. In each output that you want to include this
     * metadata, you must set ID3 metadata to Passthrough.
     *
     * @var TimedMetadataInsertion|null
     */
    private $timedMetadataInsertion;

    /**
     * @param array{
     *   AdAvailOffset?: int|null,
     *   AvailBlanking?: AvailBlanking|array|null,
     *   ColorConversion3DLUTSettings?: array<ColorConversion3DLUTSetting|array>|null,
     *   Esam?: EsamSettings|array|null,
     *   ExtendedDataServices?: ExtendedDataServices|array|null,
     *   FollowSource?: int|null,
     *   Inputs?: array<Input|array>|null,
     *   KantarWatermark?: KantarWatermarkSettings|array|null,
     *   MotionImageInserter?: MotionImageInserter|array|null,
     *   NielsenConfiguration?: NielsenConfiguration|array|null,
     *   NielsenNonLinearWatermark?: NielsenNonLinearWatermarkSettings|array|null,
     *   OutputGroups?: array<OutputGroup|array>|null,
     *   TimecodeConfig?: TimecodeConfig|array|null,
     *   TimedMetadataInsertion?: TimedMetadataInsertion|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->adAvailOffset = $input['AdAvailOffset'] ?? null;
        $this->availBlanking = isset($input['AvailBlanking']) ? AvailBlanking::create($input['AvailBlanking']) : null;
        $this->colorConversion3DlutSettings = isset($input['ColorConversion3DLUTSettings']) ? array_map([ColorConversion3DLUTSetting::class, 'create'], $input['ColorConversion3DLUTSettings']) : null;
        $this->esam = isset($input['Esam']) ? EsamSettings::create($input['Esam']) : null;
        $this->extendedDataServices = isset($input['ExtendedDataServices']) ? ExtendedDataServices::create($input['ExtendedDataServices']) : null;
        $this->followSource = $input['FollowSource'] ?? null;
        $this->inputs = isset($input['Inputs']) ? array_map([Input::class, 'create'], $input['Inputs']) : null;
        $this->kantarWatermark = isset($input['KantarWatermark']) ? KantarWatermarkSettings::create($input['KantarWatermark']) : null;
        $this->motionImageInserter = isset($input['MotionImageInserter']) ? MotionImageInserter::create($input['MotionImageInserter']) : null;
        $this->nielsenConfiguration = isset($input['NielsenConfiguration']) ? NielsenConfiguration::create($input['NielsenConfiguration']) : null;
        $this->nielsenNonLinearWatermark = isset($input['NielsenNonLinearWatermark']) ? NielsenNonLinearWatermarkSettings::create($input['NielsenNonLinearWatermark']) : null;
        $this->outputGroups = isset($input['OutputGroups']) ? array_map([OutputGroup::class, 'create'], $input['OutputGroups']) : null;
        $this->timecodeConfig = isset($input['TimecodeConfig']) ? TimecodeConfig::create($input['TimecodeConfig']) : null;
        $this->timedMetadataInsertion = isset($input['TimedMetadataInsertion']) ? TimedMetadataInsertion::create($input['TimedMetadataInsertion']) : null;
    }

    /**
     * @param array{
     *   AdAvailOffset?: int|null,
     *   AvailBlanking?: AvailBlanking|array|null,
     *   ColorConversion3DLUTSettings?: array<ColorConversion3DLUTSetting|array>|null,
     *   Esam?: EsamSettings|array|null,
     *   ExtendedDataServices?: ExtendedDataServices|array|null,
     *   FollowSource?: int|null,
     *   Inputs?: array<Input|array>|null,
     *   KantarWatermark?: KantarWatermarkSettings|array|null,
     *   MotionImageInserter?: MotionImageInserter|array|null,
     *   NielsenConfiguration?: NielsenConfiguration|array|null,
     *   NielsenNonLinearWatermark?: NielsenNonLinearWatermarkSettings|array|null,
     *   OutputGroups?: array<OutputGroup|array>|null,
     *   TimecodeConfig?: TimecodeConfig|array|null,
     *   TimedMetadataInsertion?: TimedMetadataInsertion|array|null,
     * }|JobSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdAvailOffset(): ?int
    {
        return $this->adAvailOffset;
    }

    public function getAvailBlanking(): ?AvailBlanking
    {
        return $this->availBlanking;
    }

    /**
     * @return ColorConversion3DLUTSetting[]
     */
    public function getColorConversion3DlutSettings(): array
    {
        return $this->colorConversion3DlutSettings ?? [];
    }

    public function getEsam(): ?EsamSettings
    {
        return $this->esam;
    }

    public function getExtendedDataServices(): ?ExtendedDataServices
    {
        return $this->extendedDataServices;
    }

    public function getFollowSource(): ?int
    {
        return $this->followSource;
    }

    /**
     * @return Input[]
     */
    public function getInputs(): array
    {
        return $this->inputs ?? [];
    }

    public function getKantarWatermark(): ?KantarWatermarkSettings
    {
        return $this->kantarWatermark;
    }

    public function getMotionImageInserter(): ?MotionImageInserter
    {
        return $this->motionImageInserter;
    }

    public function getNielsenConfiguration(): ?NielsenConfiguration
    {
        return $this->nielsenConfiguration;
    }

    public function getNielsenNonLinearWatermark(): ?NielsenNonLinearWatermarkSettings
    {
        return $this->nielsenNonLinearWatermark;
    }

    /**
     * @return OutputGroup[]
     */
    public function getOutputGroups(): array
    {
        return $this->outputGroups ?? [];
    }

    public function getTimecodeConfig(): ?TimecodeConfig
    {
        return $this->timecodeConfig;
    }

    public function getTimedMetadataInsertion(): ?TimedMetadataInsertion
    {
        return $this->timedMetadataInsertion;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->adAvailOffset) {
            $payload['adAvailOffset'] = $v;
        }
        if (null !== $v = $this->availBlanking) {
            $payload['availBlanking'] = $v->requestBody();
        }
        if (null !== $v = $this->colorConversion3DlutSettings) {
            $index = -1;
            $payload['colorConversion3DLUTSettings'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['colorConversion3DLUTSettings'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->esam) {
            $payload['esam'] = $v->requestBody();
        }
        if (null !== $v = $this->extendedDataServices) {
            $payload['extendedDataServices'] = $v->requestBody();
        }
        if (null !== $v = $this->followSource) {
            $payload['followSource'] = $v;
        }
        if (null !== $v = $this->inputs) {
            $index = -1;
            $payload['inputs'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['inputs'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->kantarWatermark) {
            $payload['kantarWatermark'] = $v->requestBody();
        }
        if (null !== $v = $this->motionImageInserter) {
            $payload['motionImageInserter'] = $v->requestBody();
        }
        if (null !== $v = $this->nielsenConfiguration) {
            $payload['nielsenConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->nielsenNonLinearWatermark) {
            $payload['nielsenNonLinearWatermark'] = $v->requestBody();
        }
        if (null !== $v = $this->outputGroups) {
            $index = -1;
            $payload['outputGroups'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['outputGroups'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->timecodeConfig) {
            $payload['timecodeConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->timedMetadataInsertion) {
            $payload['timedMetadataInsertion'] = $v->requestBody();
        }

        return $payload;
    }
}
