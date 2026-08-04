<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CmfcAudioDuration;
use AsyncAws\MediaConvert\Enum\MovClapAtom;
use AsyncAws\MediaConvert\Enum\MovCslgAtom;
use AsyncAws\MediaConvert\Enum\MovMpeg2FourCCControl;
use AsyncAws\MediaConvert\Enum\MovPaddingControl;
use AsyncAws\MediaConvert\Enum\MovReference;

/**
 * These settings relate to your QuickTime MOV output container.
 */
final class MovSettings
{
    /**
     * Specify this setting only when your output will be consumed by a downstream repackaging workflow that is sensitive to
     * very small duration differences between video and audio. For this situation, choose Match video duration. In all
     * other cases, keep the default value, Default codec duration. When you choose Match video duration, MediaConvert pads
     * the output audio streams with silence or trims them to ensure that the total duration of each audio stream is at
     * least as long as the total duration of the video stream. After padding or trimming, the audio stream duration is no
     * more than one frame longer than the video stream. MediaConvert applies audio padding or trimming only to the end of
     * the last segment of the output. For unsegmented outputs, MediaConvert adds padding only to the end of the file. When
     * you keep the default value, any minor discrepancies between audio and video duration will depend on your output audio
     * codec.
     *
     * @var CmfcAudioDuration::*|null
     */
    private $audioDuration;

    /**
     * When enabled, include 'clap' atom if appropriate for the video output settings.
     *
     * @var MovClapAtom::*|null
     */
    private $clapAtom;

    /**
     * When enabled, file composition times will start at zero, composition times in the 'ctts' (composition time to sample)
     * box for B-frames will be negative, and a 'cslg' (composition shift least greatest) box will be included per 14496-1
     * amendment 1. This improves compatibility with Apple players and tools.
     *
     * @var MovCslgAtom::*|null
     */
    private $cslgAtom;

    /**
     * When set to XDCAM, writes MPEG2 video streams into the QuickTime file using XDCAM fourcc codes. This increases
     * compatibility with Apple editors and players, but may decrease compatibility with other players. Only applicable when
     * the video codec is MPEG2.
     *
     * @var MovMpeg2FourCCControl::*|null
     */
    private $mpeg2FourccControl;

    /**
     * Unless you need Omneon compatibility: Keep the default value, None. To make this output compatible with Omneon:
     * Choose Omneon. When you do, MediaConvert increases the length of the 'elst' edit list atom. Note that this might
     * cause file rejections when a recipient of the output file doesn't expect this extra padding.
     *
     * @var MovPaddingControl::*|null
     */
    private $paddingControl;

    /**
     * Always keep the default value (SELF_CONTAINED) for this setting.
     *
     * @var MovReference::*|null
     */
    private $reference;

    /**
     * @param array{
     *   AudioDuration?: CmfcAudioDuration::*|null,
     *   ClapAtom?: MovClapAtom::*|null,
     *   CslgAtom?: MovCslgAtom::*|null,
     *   Mpeg2FourCCControl?: MovMpeg2FourCCControl::*|null,
     *   PaddingControl?: MovPaddingControl::*|null,
     *   Reference?: MovReference::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDuration = $input['AudioDuration'] ?? null;
        $this->clapAtom = $input['ClapAtom'] ?? null;
        $this->cslgAtom = $input['CslgAtom'] ?? null;
        $this->mpeg2FourccControl = $input['Mpeg2FourCCControl'] ?? null;
        $this->paddingControl = $input['PaddingControl'] ?? null;
        $this->reference = $input['Reference'] ?? null;
    }

    /**
     * @param array{
     *   AudioDuration?: CmfcAudioDuration::*|null,
     *   ClapAtom?: MovClapAtom::*|null,
     *   CslgAtom?: MovCslgAtom::*|null,
     *   Mpeg2FourCCControl?: MovMpeg2FourCCControl::*|null,
     *   PaddingControl?: MovPaddingControl::*|null,
     *   Reference?: MovReference::*|null,
     * }|MovSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CmfcAudioDuration::*|null
     */
    public function getAudioDuration(): ?string
    {
        return $this->audioDuration;
    }

    /**
     * @return MovClapAtom::*|null
     */
    public function getClapAtom(): ?string
    {
        return $this->clapAtom;
    }

    /**
     * @return MovCslgAtom::*|null
     */
    public function getCslgAtom(): ?string
    {
        return $this->cslgAtom;
    }

    /**
     * @return MovMpeg2FourCCControl::*|null
     */
    public function getMpeg2FourccControl(): ?string
    {
        return $this->mpeg2FourccControl;
    }

    /**
     * @return MovPaddingControl::*|null
     */
    public function getPaddingControl(): ?string
    {
        return $this->paddingControl;
    }

    /**
     * @return MovReference::*|null
     */
    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDuration) {
            if (!CmfcAudioDuration::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDuration" for "%s". The value "%s" is not a valid "CmfcAudioDuration".', __CLASS__, $v));
            }
            $payload['audioDuration'] = $v;
        }
        if (null !== $v = $this->clapAtom) {
            if (!MovClapAtom::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "clapAtom" for "%s". The value "%s" is not a valid "MovClapAtom".', __CLASS__, $v));
            }
            $payload['clapAtom'] = $v;
        }
        if (null !== $v = $this->cslgAtom) {
            if (!MovCslgAtom::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "cslgAtom" for "%s". The value "%s" is not a valid "MovCslgAtom".', __CLASS__, $v));
            }
            $payload['cslgAtom'] = $v;
        }
        if (null !== $v = $this->mpeg2FourccControl) {
            if (!MovMpeg2FourCCControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "mpeg2FourCCControl" for "%s". The value "%s" is not a valid "MovMpeg2FourCCControl".', __CLASS__, $v));
            }
            $payload['mpeg2FourCCControl'] = $v;
        }
        if (null !== $v = $this->paddingControl) {
            if (!MovPaddingControl::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "paddingControl" for "%s". The value "%s" is not a valid "MovPaddingControl".', __CLASS__, $v));
            }
            $payload['paddingControl'] = $v;
        }
        if (null !== $v = $this->reference) {
            if (!MovReference::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "reference" for "%s". The value "%s" is not a valid "MovReference".', __CLASS__, $v));
            }
            $payload['reference'] = $v;
        }

        return $payload;
    }
}
