<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\AdvancedInputFilter;
use AsyncAws\MediaConvert\Enum\InputDeblockFilter;
use AsyncAws\MediaConvert\Enum\InputDenoiseFilter;
use AsyncAws\MediaConvert\Enum\InputFilterEnable;
use AsyncAws\MediaConvert\Enum\InputPsiControl;
use AsyncAws\MediaConvert\Enum\InputScanType;
use AsyncAws\MediaConvert\Enum\InputTimecodeSource;

/**
 * Use inputs to define the source files used in your transcoding job. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/specify-input-settings.html. You can use multiple video inputs to
 * do input stitching. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/assembling-multiple-inputs-and-input-clips.html.
 */
final class Input
{
    /**
     * Use to remove noise, blocking, blurriness, or ringing from your input as a pre-filter step before encoding. The
     * Advanced input filter removes more types of compression artifacts and is an improvement when compared to basic
     * Deblock and Denoise filters. To remove video compression artifacts from your input and improve the video quality:
     * Choose Enabled. Additionally, this filter can help increase the video quality of your output relative to its bitrate,
     * since noisy inputs are more complex and require more bits to encode. To help restore loss of detail after applying
     * the filter, you can optionally add texture or sharpening as an additional step. Jobs that use this feature incur
     * pro-tier pricing. To not apply advanced input filtering: Choose Disabled. Note that you can still apply basic
     * filtering with Deblock and Denoise.
     *
     * @var AdvancedInputFilter::*|null
     */
    private $advancedInputFilter;

    /**
     * Optional settings for Advanced input filter when you set Advanced input filter to Enabled.
     *
     * @var AdvancedInputFilterSettings|null
     */
    private $advancedInputFilterSettings;

    /**
     * Use audio selector groups to combine multiple sidecar audio inputs so that you can assign them to a single output
     * audio tab. Note that, if you're working with embedded audio, it's simpler to assign multiple input tracks into a
     * single audio selector rather than use an audio selector group.
     *
     * @var array<string, AudioSelectorGroup>|null
     */
    private $audioSelectorGroups;

    /**
     * Use Audio selectors to specify a track or set of tracks from the input that you will use in your outputs. You can use
     * multiple Audio selectors per input.
     *
     * @var array<string, AudioSelector>|null
     */
    private $audioSelectors;

    /**
     * Use captions selectors to specify the captions data from your input that you use in your outputs. You can use up to
     * 100 captions selectors per input.
     *
     * @var array<string, CaptionSelector>|null
     */
    private $captionSelectors;

    /**
     * Use Cropping selection to specify the video area that the service will include in the output video frame. If you
     * specify a value here, it will override any value that you specify in the output setting Cropping selection.
     *
     * @var Rectangle|null
     */
    private $crop;

    /**
     * Enable Deblock to produce smoother motion in the output. Default is disabled. Only manually controllable for MPEG2
     * and uncompressed video inputs.
     *
     * @var InputDeblockFilter::*|null
     */
    private $deblockFilter;

    /**
     * Settings for decrypting any input files that you encrypt before you upload them to Amazon S3. MediaConvert can
     * decrypt files only when you use AWS Key Management Service (KMS) to encrypt the data key that you use to encrypt your
     * content.
     *
     * @var InputDecryptionSettings|null
     */
    private $decryptionSettings;

    /**
     * Enable Denoise to filter noise from the input. Default is disabled. Only applicable to MPEG2, H.264, H.265, and
     * uncompressed video inputs.
     *
     * @var InputDenoiseFilter::*|null
     */
    private $denoiseFilter;

    /**
     * Use this setting only when your video source has Dolby Vision studio mastering metadata that is carried in a separate
     * XML file. Specify the Amazon S3 location for the metadata XML file. MediaConvert uses this file to provide global and
     * frame-level metadata for Dolby Vision preprocessing. When you specify a file here and your input also has interleaved
     * global and frame level metadata, MediaConvert ignores the interleaved metadata and uses only the the metadata from
     * this external XML file. Note that your IAM service role must grant MediaConvert read permissions to this file. For
     * more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/iam-role.html.
     *
     * @var string|null
     */
    private $dolbyVisionMetadataXml;

    /**
     * Use Dynamic audio selectors when you do not know the track layout of your source when you submit your job, but want
     * to select multiple audio tracks. When you include an audio track in your output and specify this Dynamic audio
     * selector as the Audio source, MediaConvert creates an output audio track for each dynamically selected track. Note
     * that when you include a Dynamic audio selector for two or more inputs, each input must have the same number of audio
     * tracks and audio channels.
     *
     * @var array<string, DynamicAudioSelector>|null
     */
    private $dynamicAudioSelectors;

    /**
     * Specify the source file for your transcoding job. You can use multiple inputs in a single job. The service
     * concatenates these inputs, in the order that you specify them in the job, to create the outputs. For standard inputs,
     * provide the path to your S3, HTTP, or HTTPS source file. For example, s3://amzn-s3-demo-bucket/input.mp4 for an
     * Amazon S3 input or https://example.com/input.mp4 for an HTTPS input. For TAMS inputs, specify the HTTPS endpoint of
     * your TAMS server. For example, https://tams-server.example.com . When you do, also specify Source ID, Timerange, GAP
     * handling, and the Authorization connection ARN under TAMS settings. (Don't include these parameters in the Input file
     * URL.) For IMF inputs, specify your input by providing the path to your CPL. For example,
     * s3://amzn-s3-demo-bucket/vf/cpl.xml . If the CPL is in an incomplete IMP, make sure to use Supplemental IMPsto
     * specify any supplemental IMPs that contain assets referenced by the CPL.
     *
     * @var string|null
     */
    private $fileInput;

    /**
     * Specify whether to apply input filtering to improve the video quality of your input. To apply filtering depending on
     * your input type and quality: Choose Auto. To apply no filtering: Choose Disable. To apply filtering regardless of
     * your input type and quality: Choose Force. When you do, you must also specify a value for Filter strength.
     *
     * @var InputFilterEnable::*|null
     */
    private $filterEnable;

    /**
     * Specify the strength of the input filter. To apply an automatic amount of filtering based the compression artifacts
     * measured in your input: We recommend that you leave Filter strength blank and set Filter enable to Auto. To manually
     * apply filtering: Enter a value from 1 to 5, where 1 is the least amount of filtering and 5 is the most. The value
     * that you enter applies to the strength of the Deblock or Denoise filters, or to the strength of the Advanced input
     * filter.
     *
     * @var int|null
     */
    private $filterStrength;

    /**
     * Enable the image inserter feature to include a graphic overlay on your video. Enable or disable this feature for each
     * input individually. This setting is disabled by default.
     *
     * @var ImageInserter|null
     */
    private $imageInserter;

    /**
     * Contains sets of start and end times that together specify a portion of the input to be used in the outputs. If you
     * provide only a start time, the clip will be the entire input from that point to the end. If you provide only an end
     * time, it will be the entire input up to that point. When you specify more than one input clip, the transcoding
     * service creates the job outputs by stringing the clips together in the order you specify them.
     *
     * @var InputClipping[]|null
     */
    private $inputClippings;

    /**
     * When you have a progressive segmented frame (PsF) input, use this setting to flag the input as PsF. MediaConvert
     * doesn't automatically detect PsF. Therefore, flagging your input as PsF results in better preservation of video
     * quality when you do deinterlacing and frame rate conversion. If you don't specify, the default value is Auto. Auto is
     * the correct setting for all inputs that are not PsF. Don't set this value to PsF when your input is interlaced. Doing
     * so creates horizontal interlacing artifacts.
     *
     * @var InputScanType::*|null
     */
    private $inputScanType;

    /**
     * Use Selection placement to define the video area in your output frame. The area outside of the rectangle that you
     * specify here is black. If you specify a value here, it will override any value that you specify in the output setting
     * Selection placement. If you specify a value here, this will override any AFD values in your input, even if you set
     * Respond to AFD to Respond. If you specify a value here, this will ignore anything that you specify for the setting
     * Scaling Behavior.
     *
     * @var Rectangle|null
     */
    private $position;

    /**
     * Use Program to select a specific program from within a multi-program transport stream. Note that Quad 4K is not
     * currently supported. Default is the first program within the transport stream. If the program you specify doesn't
     * exist, the transcoding service will use this default.
     *
     * @var int|null
     */
    private $programNumber;

    /**
     * Set PSI control for transport stream inputs to specify which data the demux process to scans.
     * * Ignore PSI - Scan all PIDs for audio and video.
     * * Use PSI - Scan only PSI data.
     *
     * @var InputPsiControl::*|null
     */
    private $psiControl;

    /**
     * Provide a list of any necessary supplemental IMPs. You need supplemental IMPs if the CPL that you're using for your
     * input is in an incomplete IMP. Specify either the supplemental IMP directories with a trailing slash or the
     * ASSETMAP.xml files. For example ["s3://bucket/ov/", "s3://bucket/vf2/ASSETMAP.xml"]. You don't need to specify the
     * IMP that contains your input CPL, because the service automatically detects it.
     *
     * @var string[]|null
     */
    private $supplementalImps;

    /**
     * Specify a Time Addressable Media Store (TAMS) server as an input source. TAMS is an open-source API specification
     * that provides access to time-segmented media content. Use TAMS to retrieve specific time ranges from live or archived
     * media streams. When you specify TAMS settings, MediaConvert connects to your TAMS server, retrieves the media
     * segments for your specified time range, and processes them as a single input. This enables workflows like extracting
     * clips from live streams or processing specific portions of archived content. To use TAMS, you must: 1. Have access to
     * a TAMS-compliant server 2. Specify the server URL in the Input file URL field 3. Provide the required SourceId and
     * Timerange parameters 4. Configure authentication, if your TAMS server requires it.
     *
     * @var InputTamsSettings|null
     */
    private $tamsSettings;

    /**
     * Use this Timecode source setting, located under the input settings, to specify how the service counts input video
     * frames. This input frame count affects only the behavior of features that apply to a single input at a time, such as
     * input clipping and synchronizing some captions formats. Choose Embedded to use the timecodes in your input video.
     * Choose Start at zero to start the first frame at zero. Choose Specified start to start the first frame at the
     * timecode that you specify in the setting Start timecode. If you don't specify a value for Timecode source, the
     * service will use Embedded by default. For more information about timecodes, see
     * https://docs.aws.amazon.com/console/mediaconvert/timecode.
     *
     * @var InputTimecodeSource::*|null
     */
    private $timecodeSource;

    /**
     * Specify the timecode that you want the service to use for this input's initial frame. To use this setting, you must
     * set the Timecode source setting, located under the input settings, to Specified start. For more information about
     * timecodes, see https://docs.aws.amazon.com/console/mediaconvert/timecode.
     *
     * @var string|null
     */
    private $timecodeStart;

    /**
     * When you include Video generator, MediaConvert creates a video input with black frames. Use this setting if you do
     * not have a video input or if you want to add black video frames before, or after, other inputs. You can specify Video
     * generator, or you can specify an Input file, but you cannot specify both. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/video-generator.html.
     *
     * @var InputVideoGenerator|null
     */
    private $videoGenerator;

    /**
     * Contains an array of video overlays.
     *
     * @var VideoOverlay[]|null
     */
    private $videoOverlays;

    /**
     * Input video selectors contain the video settings for the input. Each of your inputs can have up to one video
     * selector.
     *
     * @var VideoSelector|null
     */
    private $videoSelector;

    /**
     * @param array{
     *   AdvancedInputFilter?: AdvancedInputFilter::*|null,
     *   AdvancedInputFilterSettings?: AdvancedInputFilterSettings|array|null,
     *   AudioSelectorGroups?: array<string, AudioSelectorGroup|array>|null,
     *   AudioSelectors?: array<string, AudioSelector|array>|null,
     *   CaptionSelectors?: array<string, CaptionSelector|array>|null,
     *   Crop?: Rectangle|array|null,
     *   DeblockFilter?: InputDeblockFilter::*|null,
     *   DecryptionSettings?: InputDecryptionSettings|array|null,
     *   DenoiseFilter?: InputDenoiseFilter::*|null,
     *   DolbyVisionMetadataXml?: string|null,
     *   DynamicAudioSelectors?: array<string, DynamicAudioSelector|array>|null,
     *   FileInput?: string|null,
     *   FilterEnable?: InputFilterEnable::*|null,
     *   FilterStrength?: int|null,
     *   ImageInserter?: ImageInserter|array|null,
     *   InputClippings?: array<InputClipping|array>|null,
     *   InputScanType?: InputScanType::*|null,
     *   Position?: Rectangle|array|null,
     *   ProgramNumber?: int|null,
     *   PsiControl?: InputPsiControl::*|null,
     *   SupplementalImps?: string[]|null,
     *   TamsSettings?: InputTamsSettings|array|null,
     *   TimecodeSource?: InputTimecodeSource::*|null,
     *   TimecodeStart?: string|null,
     *   VideoGenerator?: InputVideoGenerator|array|null,
     *   VideoOverlays?: array<VideoOverlay|array>|null,
     *   VideoSelector?: VideoSelector|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->advancedInputFilter = $input['AdvancedInputFilter'] ?? null;
        $this->advancedInputFilterSettings = isset($input['AdvancedInputFilterSettings']) ? AdvancedInputFilterSettings::create($input['AdvancedInputFilterSettings']) : null;
        $this->audioSelectorGroups = isset($input['AudioSelectorGroups']) ? array_map([AudioSelectorGroup::class, 'create'], $input['AudioSelectorGroups']) : null;
        $this->audioSelectors = isset($input['AudioSelectors']) ? array_map([AudioSelector::class, 'create'], $input['AudioSelectors']) : null;
        $this->captionSelectors = isset($input['CaptionSelectors']) ? array_map([CaptionSelector::class, 'create'], $input['CaptionSelectors']) : null;
        $this->crop = isset($input['Crop']) ? Rectangle::create($input['Crop']) : null;
        $this->deblockFilter = $input['DeblockFilter'] ?? null;
        $this->decryptionSettings = isset($input['DecryptionSettings']) ? InputDecryptionSettings::create($input['DecryptionSettings']) : null;
        $this->denoiseFilter = $input['DenoiseFilter'] ?? null;
        $this->dolbyVisionMetadataXml = $input['DolbyVisionMetadataXml'] ?? null;
        $this->dynamicAudioSelectors = isset($input['DynamicAudioSelectors']) ? array_map([DynamicAudioSelector::class, 'create'], $input['DynamicAudioSelectors']) : null;
        $this->fileInput = $input['FileInput'] ?? null;
        $this->filterEnable = $input['FilterEnable'] ?? null;
        $this->filterStrength = $input['FilterStrength'] ?? null;
        $this->imageInserter = isset($input['ImageInserter']) ? ImageInserter::create($input['ImageInserter']) : null;
        $this->inputClippings = isset($input['InputClippings']) ? array_map([InputClipping::class, 'create'], $input['InputClippings']) : null;
        $this->inputScanType = $input['InputScanType'] ?? null;
        $this->position = isset($input['Position']) ? Rectangle::create($input['Position']) : null;
        $this->programNumber = $input['ProgramNumber'] ?? null;
        $this->psiControl = $input['PsiControl'] ?? null;
        $this->supplementalImps = $input['SupplementalImps'] ?? null;
        $this->tamsSettings = isset($input['TamsSettings']) ? InputTamsSettings::create($input['TamsSettings']) : null;
        $this->timecodeSource = $input['TimecodeSource'] ?? null;
        $this->timecodeStart = $input['TimecodeStart'] ?? null;
        $this->videoGenerator = isset($input['VideoGenerator']) ? InputVideoGenerator::create($input['VideoGenerator']) : null;
        $this->videoOverlays = isset($input['VideoOverlays']) ? array_map([VideoOverlay::class, 'create'], $input['VideoOverlays']) : null;
        $this->videoSelector = isset($input['VideoSelector']) ? VideoSelector::create($input['VideoSelector']) : null;
    }

    /**
     * @param array{
     *   AdvancedInputFilter?: AdvancedInputFilter::*|null,
     *   AdvancedInputFilterSettings?: AdvancedInputFilterSettings|array|null,
     *   AudioSelectorGroups?: array<string, AudioSelectorGroup|array>|null,
     *   AudioSelectors?: array<string, AudioSelector|array>|null,
     *   CaptionSelectors?: array<string, CaptionSelector|array>|null,
     *   Crop?: Rectangle|array|null,
     *   DeblockFilter?: InputDeblockFilter::*|null,
     *   DecryptionSettings?: InputDecryptionSettings|array|null,
     *   DenoiseFilter?: InputDenoiseFilter::*|null,
     *   DolbyVisionMetadataXml?: string|null,
     *   DynamicAudioSelectors?: array<string, DynamicAudioSelector|array>|null,
     *   FileInput?: string|null,
     *   FilterEnable?: InputFilterEnable::*|null,
     *   FilterStrength?: int|null,
     *   ImageInserter?: ImageInserter|array|null,
     *   InputClippings?: array<InputClipping|array>|null,
     *   InputScanType?: InputScanType::*|null,
     *   Position?: Rectangle|array|null,
     *   ProgramNumber?: int|null,
     *   PsiControl?: InputPsiControl::*|null,
     *   SupplementalImps?: string[]|null,
     *   TamsSettings?: InputTamsSettings|array|null,
     *   TimecodeSource?: InputTimecodeSource::*|null,
     *   TimecodeStart?: string|null,
     *   VideoGenerator?: InputVideoGenerator|array|null,
     *   VideoOverlays?: array<VideoOverlay|array>|null,
     *   VideoSelector?: VideoSelector|array|null,
     * }|Input $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AdvancedInputFilter::*|null
     */
    public function getAdvancedInputFilter(): ?string
    {
        return $this->advancedInputFilter;
    }

    public function getAdvancedInputFilterSettings(): ?AdvancedInputFilterSettings
    {
        return $this->advancedInputFilterSettings;
    }

    /**
     * @return array<string, AudioSelectorGroup>
     */
    public function getAudioSelectorGroups(): array
    {
        return $this->audioSelectorGroups ?? [];
    }

    /**
     * @return array<string, AudioSelector>
     */
    public function getAudioSelectors(): array
    {
        return $this->audioSelectors ?? [];
    }

    /**
     * @return array<string, CaptionSelector>
     */
    public function getCaptionSelectors(): array
    {
        return $this->captionSelectors ?? [];
    }

    public function getCrop(): ?Rectangle
    {
        return $this->crop;
    }

    /**
     * @return InputDeblockFilter::*|null
     */
    public function getDeblockFilter(): ?string
    {
        return $this->deblockFilter;
    }

    public function getDecryptionSettings(): ?InputDecryptionSettings
    {
        return $this->decryptionSettings;
    }

    /**
     * @return InputDenoiseFilter::*|null
     */
    public function getDenoiseFilter(): ?string
    {
        return $this->denoiseFilter;
    }

    public function getDolbyVisionMetadataXml(): ?string
    {
        return $this->dolbyVisionMetadataXml;
    }

    /**
     * @return array<string, DynamicAudioSelector>
     */
    public function getDynamicAudioSelectors(): array
    {
        return $this->dynamicAudioSelectors ?? [];
    }

    public function getFileInput(): ?string
    {
        return $this->fileInput;
    }

    /**
     * @return InputFilterEnable::*|null
     */
    public function getFilterEnable(): ?string
    {
        return $this->filterEnable;
    }

    public function getFilterStrength(): ?int
    {
        return $this->filterStrength;
    }

    public function getImageInserter(): ?ImageInserter
    {
        return $this->imageInserter;
    }

    /**
     * @return InputClipping[]
     */
    public function getInputClippings(): array
    {
        return $this->inputClippings ?? [];
    }

    /**
     * @return InputScanType::*|null
     */
    public function getInputScanType(): ?string
    {
        return $this->inputScanType;
    }

    public function getPosition(): ?Rectangle
    {
        return $this->position;
    }

    public function getProgramNumber(): ?int
    {
        return $this->programNumber;
    }

    /**
     * @return InputPsiControl::*|null
     */
    public function getPsiControl(): ?string
    {
        return $this->psiControl;
    }

    /**
     * @return string[]
     */
    public function getSupplementalImps(): array
    {
        return $this->supplementalImps ?? [];
    }

    public function getTamsSettings(): ?InputTamsSettings
    {
        return $this->tamsSettings;
    }

    /**
     * @return InputTimecodeSource::*|null
     */
    public function getTimecodeSource(): ?string
    {
        return $this->timecodeSource;
    }

    public function getTimecodeStart(): ?string
    {
        return $this->timecodeStart;
    }

    public function getVideoGenerator(): ?InputVideoGenerator
    {
        return $this->videoGenerator;
    }

    /**
     * @return VideoOverlay[]
     */
    public function getVideoOverlays(): array
    {
        return $this->videoOverlays ?? [];
    }

    public function getVideoSelector(): ?VideoSelector
    {
        return $this->videoSelector;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->advancedInputFilter) {
            if (!AdvancedInputFilter::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "advancedInputFilter" for "%s". The value "%s" is not a valid "AdvancedInputFilter".', __CLASS__, $v));
            }
            $payload['advancedInputFilter'] = $v;
        }
        if (null !== $v = $this->advancedInputFilterSettings) {
            $payload['advancedInputFilterSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->audioSelectorGroups) {
            if (empty($v)) {
                $payload['audioSelectorGroups'] = new \stdClass();
            } else {
                $payload['audioSelectorGroups'] = [];
                foreach ($v as $name => $mv) {
                    $payload['audioSelectorGroups'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->audioSelectors) {
            if (empty($v)) {
                $payload['audioSelectors'] = new \stdClass();
            } else {
                $payload['audioSelectors'] = [];
                foreach ($v as $name => $mv) {
                    $payload['audioSelectors'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->captionSelectors) {
            if (empty($v)) {
                $payload['captionSelectors'] = new \stdClass();
            } else {
                $payload['captionSelectors'] = [];
                foreach ($v as $name => $mv) {
                    $payload['captionSelectors'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->crop) {
            $payload['crop'] = $v->requestBody();
        }
        if (null !== $v = $this->deblockFilter) {
            if (!InputDeblockFilter::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "deblockFilter" for "%s". The value "%s" is not a valid "InputDeblockFilter".', __CLASS__, $v));
            }
            $payload['deblockFilter'] = $v;
        }
        if (null !== $v = $this->decryptionSettings) {
            $payload['decryptionSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->denoiseFilter) {
            if (!InputDenoiseFilter::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "denoiseFilter" for "%s". The value "%s" is not a valid "InputDenoiseFilter".', __CLASS__, $v));
            }
            $payload['denoiseFilter'] = $v;
        }
        if (null !== $v = $this->dolbyVisionMetadataXml) {
            $payload['dolbyVisionMetadataXml'] = $v;
        }
        if (null !== $v = $this->dynamicAudioSelectors) {
            if (empty($v)) {
                $payload['dynamicAudioSelectors'] = new \stdClass();
            } else {
                $payload['dynamicAudioSelectors'] = [];
                foreach ($v as $name => $mv) {
                    $payload['dynamicAudioSelectors'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->fileInput) {
            $payload['fileInput'] = $v;
        }
        if (null !== $v = $this->filterEnable) {
            if (!InputFilterEnable::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "filterEnable" for "%s". The value "%s" is not a valid "InputFilterEnable".', __CLASS__, $v));
            }
            $payload['filterEnable'] = $v;
        }
        if (null !== $v = $this->filterStrength) {
            $payload['filterStrength'] = $v;
        }
        if (null !== $v = $this->imageInserter) {
            $payload['imageInserter'] = $v->requestBody();
        }
        if (null !== $v = $this->inputClippings) {
            $index = -1;
            $payload['inputClippings'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['inputClippings'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->inputScanType) {
            if (!InputScanType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "inputScanType" for "%s". The value "%s" is not a valid "InputScanType".', __CLASS__, $v));
            }
            $payload['inputScanType'] = $v;
        }
        if (null !== $v = $this->position) {
            $payload['position'] = $v->requestBody();
        }
        if (null !== $v = $this->programNumber) {
            $payload['programNumber'] = $v;
        }
        if (null !== $v = $this->psiControl) {
            if (!InputPsiControl::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "psiControl" for "%s". The value "%s" is not a valid "InputPsiControl".', __CLASS__, $v));
            }
            $payload['psiControl'] = $v;
        }
        if (null !== $v = $this->supplementalImps) {
            $index = -1;
            $payload['supplementalImps'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['supplementalImps'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->tamsSettings) {
            $payload['tamsSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->timecodeSource) {
            if (!InputTimecodeSource::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "timecodeSource" for "%s". The value "%s" is not a valid "InputTimecodeSource".', __CLASS__, $v));
            }
            $payload['timecodeSource'] = $v;
        }
        if (null !== $v = $this->timecodeStart) {
            $payload['timecodeStart'] = $v;
        }
        if (null !== $v = $this->videoGenerator) {
            $payload['videoGenerator'] = $v->requestBody();
        }
        if (null !== $v = $this->videoOverlays) {
            $index = -1;
            $payload['videoOverlays'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['videoOverlays'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->videoSelector) {
            $payload['videoSelector'] = $v->requestBody();
        }

        return $payload;
    }
}
