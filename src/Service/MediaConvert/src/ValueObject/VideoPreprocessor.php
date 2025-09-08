<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Find additional transcoding features under Preprocessors. Enable the features at each output individually. These
 * features are disabled by default.
 */
final class VideoPreprocessor
{
    /**
     * Use these settings to convert the color space or to modify properties such as hue and contrast for this output. For
     * more information, see https://docs.aws.amazon.com/mediaconvert/latest/ug/converting-the-color-space.html.
     *
     * @var ColorCorrector|null
     */
    private $colorCorrector;

    /**
     * Use the deinterlacer to produce smoother motion and a clearer picture. For more information, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/working-with-scan-type.html.
     *
     * @var Deinterlacer|null
     */
    private $deinterlacer;

    /**
     * Enable Dolby Vision feature to produce Dolby Vision compatible video output.
     *
     * @var DolbyVision|null
     */
    private $dolbyVision;

    /**
     * Enable HDR10+ analysis and metadata injection. Compatible with HEVC only.
     *
     * @var Hdr10Plus|null
     */
    private $hdr10Plus;

    /**
     * Enable the Image inserter feature to include a graphic overlay on your video. Enable or disable this feature for each
     * output individually. This setting is disabled by default.
     *
     * @var ImageInserter|null
     */
    private $imageInserter;

    /**
     * Enable the Noise reducer feature to remove noise from your video output if necessary. Enable or disable this feature
     * for each output individually. This setting is disabled by default. When you enable Noise reducer, you must also
     * select a value for Noise reducer filter. For AVC outputs, when you include Noise reducer, you cannot include the
     * Bandwidth reduction filter.
     *
     * @var NoiseReducer|null
     */
    private $noiseReducer;

    /**
     * If you work with a third party video watermarking partner, use the group of settings that correspond with your
     * watermarking partner to include watermarks in your output.
     *
     * @var PartnerWatermarking|null
     */
    private $partnerWatermarking;

    /**
     * Settings for burning the output timecode and specified prefix into the output.
     *
     * @var TimecodeBurnin|null
     */
    private $timecodeBurnin;

    /**
     * @param array{
     *   ColorCorrector?: ColorCorrector|array|null,
     *   Deinterlacer?: Deinterlacer|array|null,
     *   DolbyVision?: DolbyVision|array|null,
     *   Hdr10Plus?: Hdr10Plus|array|null,
     *   ImageInserter?: ImageInserter|array|null,
     *   NoiseReducer?: NoiseReducer|array|null,
     *   PartnerWatermarking?: PartnerWatermarking|array|null,
     *   TimecodeBurnin?: TimecodeBurnin|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->colorCorrector = isset($input['ColorCorrector']) ? ColorCorrector::create($input['ColorCorrector']) : null;
        $this->deinterlacer = isset($input['Deinterlacer']) ? Deinterlacer::create($input['Deinterlacer']) : null;
        $this->dolbyVision = isset($input['DolbyVision']) ? DolbyVision::create($input['DolbyVision']) : null;
        $this->hdr10Plus = isset($input['Hdr10Plus']) ? Hdr10Plus::create($input['Hdr10Plus']) : null;
        $this->imageInserter = isset($input['ImageInserter']) ? ImageInserter::create($input['ImageInserter']) : null;
        $this->noiseReducer = isset($input['NoiseReducer']) ? NoiseReducer::create($input['NoiseReducer']) : null;
        $this->partnerWatermarking = isset($input['PartnerWatermarking']) ? PartnerWatermarking::create($input['PartnerWatermarking']) : null;
        $this->timecodeBurnin = isset($input['TimecodeBurnin']) ? TimecodeBurnin::create($input['TimecodeBurnin']) : null;
    }

    /**
     * @param array{
     *   ColorCorrector?: ColorCorrector|array|null,
     *   Deinterlacer?: Deinterlacer|array|null,
     *   DolbyVision?: DolbyVision|array|null,
     *   Hdr10Plus?: Hdr10Plus|array|null,
     *   ImageInserter?: ImageInserter|array|null,
     *   NoiseReducer?: NoiseReducer|array|null,
     *   PartnerWatermarking?: PartnerWatermarking|array|null,
     *   TimecodeBurnin?: TimecodeBurnin|array|null,
     * }|VideoPreprocessor $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getColorCorrector(): ?ColorCorrector
    {
        return $this->colorCorrector;
    }

    public function getDeinterlacer(): ?Deinterlacer
    {
        return $this->deinterlacer;
    }

    public function getDolbyVision(): ?DolbyVision
    {
        return $this->dolbyVision;
    }

    public function getHdr10Plus(): ?Hdr10Plus
    {
        return $this->hdr10Plus;
    }

    public function getImageInserter(): ?ImageInserter
    {
        return $this->imageInserter;
    }

    public function getNoiseReducer(): ?NoiseReducer
    {
        return $this->noiseReducer;
    }

    public function getPartnerWatermarking(): ?PartnerWatermarking
    {
        return $this->partnerWatermarking;
    }

    public function getTimecodeBurnin(): ?TimecodeBurnin
    {
        return $this->timecodeBurnin;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->colorCorrector) {
            $payload['colorCorrector'] = $v->requestBody();
        }
        if (null !== $v = $this->deinterlacer) {
            $payload['deinterlacer'] = $v->requestBody();
        }
        if (null !== $v = $this->dolbyVision) {
            $payload['dolbyVision'] = $v->requestBody();
        }
        if (null !== $v = $this->hdr10Plus) {
            $payload['hdr10Plus'] = $v->requestBody();
        }
        if (null !== $v = $this->imageInserter) {
            $payload['imageInserter'] = $v->requestBody();
        }
        if (null !== $v = $this->noiseReducer) {
            $payload['noiseReducer'] = $v->requestBody();
        }
        if (null !== $v = $this->partnerWatermarking) {
            $payload['partnerWatermarking'] = $v->requestBody();
        }
        if (null !== $v = $this->timecodeBurnin) {
            $payload['timecodeBurnin'] = $v->requestBody();
        }

        return $payload;
    }
}
