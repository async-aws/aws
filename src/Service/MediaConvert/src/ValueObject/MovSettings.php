<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
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
     *   ClapAtom?: MovClapAtom::*|null,
     *   CslgAtom?: MovCslgAtom::*|null,
     *   Mpeg2FourCCControl?: MovMpeg2FourCCControl::*|null,
     *   PaddingControl?: MovPaddingControl::*|null,
     *   Reference?: MovReference::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->clapAtom = $input['ClapAtom'] ?? null;
        $this->cslgAtom = $input['CslgAtom'] ?? null;
        $this->mpeg2FourccControl = $input['Mpeg2FourCCControl'] ?? null;
        $this->paddingControl = $input['PaddingControl'] ?? null;
        $this->reference = $input['Reference'] ?? null;
    }

    /**
     * @param array{
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
