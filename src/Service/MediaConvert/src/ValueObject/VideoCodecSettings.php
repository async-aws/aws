<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\VideoCodec;

/**
 * Video codec settings contains the group of settings related to video encoding. The settings in this group vary
 * depending on the value that you choose for Video codec. For each codec enum that you choose, define the corresponding
 * settings object. The following lists the codec enum, settings object pairs. * AV1, Av1Settings * AVC_INTRA,
 * AvcIntraSettings * FRAME_CAPTURE, FrameCaptureSettings * GIF, GifSettings * H_264, H264Settings * H_265, H265Settings
 * * MPEG2, Mpeg2Settings * PRORES, ProresSettings * UNCOMPRESSED, UncompressedSettings * VC3, Vc3Settings * VP8,
 * Vp8Settings * VP9, Vp9Settings * XAVC, XavcSettings.
 */
final class VideoCodecSettings
{
    /**
     * Required when you set Codec, under VideoDescription>CodecSettings to the value AV1.
     *
     * @var Av1Settings|null
     */
    private $av1Settings;

    /**
     * Required when you choose AVC-Intra for your output video codec. For more information about the AVC-Intra settings,
     * see the relevant specification. For detailed information about SD and HD in AVC-Intra, see
     * https://ieeexplore.ieee.org/document/7290936. For information about 4K/2K in AVC-Intra, see
     * https://pro-av.panasonic.net/en/avc-ultra/AVC-ULTRAoverview.pdf.
     *
     * @var AvcIntraSettings|null
     */
    private $avcIntraSettings;

    /**
     * Specifies the video codec. This must be equal to one of the enum values defined by the object VideoCodec. To
     * passthrough the video stream of your input without any video encoding: Choose Passthrough. More information about
     * passthrough codec support and job settings requirements, see:
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/video-passthrough-feature-restrictions.html.
     *
     * @var VideoCodec::*|string|null
     */
    private $codec;

    /**
     * Required when you set Codec to the value FRAME_CAPTURE.
     *
     * @var FrameCaptureSettings|null
     */
    private $frameCaptureSettings;

    /**
     * Required when you set (Codec) under (VideoDescription)>(CodecSettings) to the value GIF.
     *
     * @var GifSettings|null
     */
    private $gifSettings;

    /**
     * Required when you set Codec to the value H_264.
     *
     * @var H264Settings|null
     */
    private $h264Settings;

    /**
     * Settings for H265 codec.
     *
     * @var H265Settings|null
     */
    private $h265Settings;

    /**
     * Required when you set Codec to the value MPEG2.
     *
     * @var Mpeg2Settings|null
     */
    private $mpeg2Settings;

    /**
     * Required when you set Codec to the value PRORES.
     *
     * @var ProresSettings|null
     */
    private $proresSettings;

    /**
     * Required when you set Codec, under VideoDescription>CodecSettings to the value UNCOMPRESSED.
     *
     * @var UncompressedSettings|null
     */
    private $uncompressedSettings;

    /**
     * Required when you set Codec to the value VC3.
     *
     * @var Vc3Settings|null
     */
    private $vc3Settings;

    /**
     * Required when you set Codec to the value VP8.
     *
     * @var Vp8Settings|null
     */
    private $vp8Settings;

    /**
     * Required when you set Codec to the value VP9.
     *
     * @var Vp9Settings|null
     */
    private $vp9Settings;

    /**
     * Required when you set Codec to the value XAVC.
     *
     * @var XavcSettings|null
     */
    private $xavcSettings;

    /**
     * @param array{
     *   Av1Settings?: null|Av1Settings|array,
     *   AvcIntraSettings?: null|AvcIntraSettings|array,
     *   Codec?: null|VideoCodec::*|string,
     *   FrameCaptureSettings?: null|FrameCaptureSettings|array,
     *   GifSettings?: null|GifSettings|array,
     *   H264Settings?: null|H264Settings|array,
     *   H265Settings?: null|H265Settings|array,
     *   Mpeg2Settings?: null|Mpeg2Settings|array,
     *   ProresSettings?: null|ProresSettings|array,
     *   UncompressedSettings?: null|UncompressedSettings|array,
     *   Vc3Settings?: null|Vc3Settings|array,
     *   Vp8Settings?: null|Vp8Settings|array,
     *   Vp9Settings?: null|Vp9Settings|array,
     *   XavcSettings?: null|XavcSettings|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->av1Settings = isset($input['Av1Settings']) ? Av1Settings::create($input['Av1Settings']) : null;
        $this->avcIntraSettings = isset($input['AvcIntraSettings']) ? AvcIntraSettings::create($input['AvcIntraSettings']) : null;
        $this->codec = $input['Codec'] ?? null;
        $this->frameCaptureSettings = isset($input['FrameCaptureSettings']) ? FrameCaptureSettings::create($input['FrameCaptureSettings']) : null;
        $this->gifSettings = isset($input['GifSettings']) ? GifSettings::create($input['GifSettings']) : null;
        $this->h264Settings = isset($input['H264Settings']) ? H264Settings::create($input['H264Settings']) : null;
        $this->h265Settings = isset($input['H265Settings']) ? H265Settings::create($input['H265Settings']) : null;
        $this->mpeg2Settings = isset($input['Mpeg2Settings']) ? Mpeg2Settings::create($input['Mpeg2Settings']) : null;
        $this->proresSettings = isset($input['ProresSettings']) ? ProresSettings::create($input['ProresSettings']) : null;
        $this->uncompressedSettings = isset($input['UncompressedSettings']) ? UncompressedSettings::create($input['UncompressedSettings']) : null;
        $this->vc3Settings = isset($input['Vc3Settings']) ? Vc3Settings::create($input['Vc3Settings']) : null;
        $this->vp8Settings = isset($input['Vp8Settings']) ? Vp8Settings::create($input['Vp8Settings']) : null;
        $this->vp9Settings = isset($input['Vp9Settings']) ? Vp9Settings::create($input['Vp9Settings']) : null;
        $this->xavcSettings = isset($input['XavcSettings']) ? XavcSettings::create($input['XavcSettings']) : null;
    }

    /**
     * @param array{
     *   Av1Settings?: null|Av1Settings|array,
     *   AvcIntraSettings?: null|AvcIntraSettings|array,
     *   Codec?: null|VideoCodec::*|string,
     *   FrameCaptureSettings?: null|FrameCaptureSettings|array,
     *   GifSettings?: null|GifSettings|array,
     *   H264Settings?: null|H264Settings|array,
     *   H265Settings?: null|H265Settings|array,
     *   Mpeg2Settings?: null|Mpeg2Settings|array,
     *   ProresSettings?: null|ProresSettings|array,
     *   UncompressedSettings?: null|UncompressedSettings|array,
     *   Vc3Settings?: null|Vc3Settings|array,
     *   Vp8Settings?: null|Vp8Settings|array,
     *   Vp9Settings?: null|Vp9Settings|array,
     *   XavcSettings?: null|XavcSettings|array,
     * }|VideoCodecSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAv1Settings(): ?Av1Settings
    {
        return $this->av1Settings;
    }

    public function getAvcIntraSettings(): ?AvcIntraSettings
    {
        return $this->avcIntraSettings;
    }

    /**
     * @return VideoCodec::*|string|null
     */
    public function getCodec(): ?string
    {
        return $this->codec;
    }

    public function getFrameCaptureSettings(): ?FrameCaptureSettings
    {
        return $this->frameCaptureSettings;
    }

    public function getGifSettings(): ?GifSettings
    {
        return $this->gifSettings;
    }

    public function getH264Settings(): ?H264Settings
    {
        return $this->h264Settings;
    }

    public function getH265Settings(): ?H265Settings
    {
        return $this->h265Settings;
    }

    public function getMpeg2Settings(): ?Mpeg2Settings
    {
        return $this->mpeg2Settings;
    }

    public function getProresSettings(): ?ProresSettings
    {
        return $this->proresSettings;
    }

    public function getUncompressedSettings(): ?UncompressedSettings
    {
        return $this->uncompressedSettings;
    }

    public function getVc3Settings(): ?Vc3Settings
    {
        return $this->vc3Settings;
    }

    public function getVp8Settings(): ?Vp8Settings
    {
        return $this->vp8Settings;
    }

    public function getVp9Settings(): ?Vp9Settings
    {
        return $this->vp9Settings;
    }

    public function getXavcSettings(): ?XavcSettings
    {
        return $this->xavcSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->av1Settings) {
            $payload['av1Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->avcIntraSettings) {
            $payload['avcIntraSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->codec) {
            if (!VideoCodec::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "codec" for "%s". The value "%s" is not a valid "VideoCodec".', __CLASS__, $v));
            }
            $payload['codec'] = $v;
        }
        if (null !== $v = $this->frameCaptureSettings) {
            $payload['frameCaptureSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->gifSettings) {
            $payload['gifSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->h264Settings) {
            $payload['h264Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->h265Settings) {
            $payload['h265Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->mpeg2Settings) {
            $payload['mpeg2Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->proresSettings) {
            $payload['proresSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->uncompressedSettings) {
            $payload['uncompressedSettings'] = $v->requestBody();
        }
        if (null !== $v = $this->vc3Settings) {
            $payload['vc3Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->vp8Settings) {
            $payload['vp8Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->vp9Settings) {
            $payload['vp9Settings'] = $v->requestBody();
        }
        if (null !== $v = $this->xavcSettings) {
            $payload['xavcSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
