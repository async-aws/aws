<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\CmfcAudioDuration;
use AsyncAws\MediaConvert\Enum\Mp4C2paManifest;
use AsyncAws\MediaConvert\Enum\Mp4CslgAtom;
use AsyncAws\MediaConvert\Enum\Mp4FreeSpaceBox;
use AsyncAws\MediaConvert\Enum\Mp4MoovPlacement;

/**
 * These settings relate to your MP4 output container. You can create audio only outputs with this container. For more
 * information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/supported-codecs-containers-audio-only.html#output-codecs-and-containers-supported-for-audio-only.
 */
final class Mp4Settings
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
     * @var CmfcAudioDuration::*|string|null
     */
    private $audioDuration;

    /**
     * When enabled, a C2PA compliant manifest will be generated, signed and embeded in the output. For more information on
     * C2PA, see https://c2pa.org/specifications/specifications/2.1/index.html.
     *
     * @var Mp4C2paManifest::*|string|null
     */
    private $c2paManifest;

    /**
     * Specify the name or ARN of the AWS Secrets Manager secret that contains your C2PA public certificate chain in PEM
     * format. Provide a valid secret name or ARN. Note that your MediaConvert service role must allow access to this
     * secret. The public certificate chain is added to the COSE header (x5chain) for signature validation. Include the
     * signer's certificate and all intermediate certificates. Do not include the root certificate. For details on COSE,
     * see: https://opensource.contentauthenticity.org/docs/manifest/signing-manifests.
     *
     * @var string|null
     */
    private $certificateSecret;

    /**
     * When enabled, file composition times will start at zero, composition times in the 'ctts' (composition time to sample)
     * box for B-frames will be negative, and a 'cslg' (composition shift least greatest) box will be included per 14496-1
     * amendment 1. This improves compatibility with Apple players and tools.
     *
     * @var Mp4CslgAtom::*|string|null
     */
    private $cslgAtom;

    /**
     * Ignore this setting unless compliance to the CTTS box version specification matters in your workflow. Specify a value
     * of 1 to set your CTTS box version to 1 and make your output compliant with the specification. When you specify a
     * value of 1, you must also set CSLG atom to the value INCLUDE. Keep the default value 0 to set your CTTS box version
     * to 0. This can provide backward compatibility for some players and packagers.
     *
     * @var int|null
     */
    private $cttsVersion;

    /**
     * Inserts a free-space box immediately after the moov box.
     *
     * @var Mp4FreeSpaceBox::*|string|null
     */
    private $freeSpaceBox;

    /**
     * To place the MOOV atom at the beginning of your output, which is useful for progressive downloading: Leave blank or
     * choose Progressive download. To place the MOOV at the end of your output: Choose Normal.
     *
     * @var Mp4MoovPlacement::*|string|null
     */
    private $moovPlacement;

    /**
     * Overrides the "Major Brand" field in the output file. Usually not necessary to specify.
     *
     * @var string|null
     */
    private $mp4MajorBrand;

    /**
     * Specify the ID or ARN of the AWS KMS key used to sign the C2PA manifest in your MP4 output. Provide a valid KMS key
     * ARN. Note that your MediaConvert service role must allow access to this key.
     *
     * @var string|null
     */
    private $signingKmsKey;

    /**
     * @param array{
     *   AudioDuration?: null|CmfcAudioDuration::*|string,
     *   C2paManifest?: null|Mp4C2paManifest::*|string,
     *   CertificateSecret?: null|string,
     *   CslgAtom?: null|Mp4CslgAtom::*|string,
     *   CttsVersion?: null|int,
     *   FreeSpaceBox?: null|Mp4FreeSpaceBox::*|string,
     *   MoovPlacement?: null|Mp4MoovPlacement::*|string,
     *   Mp4MajorBrand?: null|string,
     *   SigningKmsKey?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDuration = $input['AudioDuration'] ?? null;
        $this->c2paManifest = $input['C2paManifest'] ?? null;
        $this->certificateSecret = $input['CertificateSecret'] ?? null;
        $this->cslgAtom = $input['CslgAtom'] ?? null;
        $this->cttsVersion = $input['CttsVersion'] ?? null;
        $this->freeSpaceBox = $input['FreeSpaceBox'] ?? null;
        $this->moovPlacement = $input['MoovPlacement'] ?? null;
        $this->mp4MajorBrand = $input['Mp4MajorBrand'] ?? null;
        $this->signingKmsKey = $input['SigningKmsKey'] ?? null;
    }

    /**
     * @param array{
     *   AudioDuration?: null|CmfcAudioDuration::*|string,
     *   C2paManifest?: null|Mp4C2paManifest::*|string,
     *   CertificateSecret?: null|string,
     *   CslgAtom?: null|Mp4CslgAtom::*|string,
     *   CttsVersion?: null|int,
     *   FreeSpaceBox?: null|Mp4FreeSpaceBox::*|string,
     *   MoovPlacement?: null|Mp4MoovPlacement::*|string,
     *   Mp4MajorBrand?: null|string,
     *   SigningKmsKey?: null|string,
     * }|Mp4Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return CmfcAudioDuration::*|string|null
     */
    public function getAudioDuration(): ?string
    {
        return $this->audioDuration;
    }

    /**
     * @return Mp4C2paManifest::*|string|null
     */
    public function getC2paManifest(): ?string
    {
        return $this->c2paManifest;
    }

    public function getCertificateSecret(): ?string
    {
        return $this->certificateSecret;
    }

    /**
     * @return Mp4CslgAtom::*|string|null
     */
    public function getCslgAtom(): ?string
    {
        return $this->cslgAtom;
    }

    public function getCttsVersion(): ?int
    {
        return $this->cttsVersion;
    }

    /**
     * @return Mp4FreeSpaceBox::*|string|null
     */
    public function getFreeSpaceBox(): ?string
    {
        return $this->freeSpaceBox;
    }

    /**
     * @return Mp4MoovPlacement::*|string|null
     */
    public function getMoovPlacement(): ?string
    {
        return $this->moovPlacement;
    }

    public function getMp4MajorBrand(): ?string
    {
        return $this->mp4MajorBrand;
    }

    public function getSigningKmsKey(): ?string
    {
        return $this->signingKmsKey;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDuration) {
            if (!CmfcAudioDuration::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDuration" for "%s". The value "%s" is not a valid "CmfcAudioDuration".', __CLASS__, $v));
            }
            $payload['audioDuration'] = $v;
        }
        if (null !== $v = $this->c2paManifest) {
            if (!Mp4C2paManifest::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "c2paManifest" for "%s". The value "%s" is not a valid "Mp4C2paManifest".', __CLASS__, $v));
            }
            $payload['c2paManifest'] = $v;
        }
        if (null !== $v = $this->certificateSecret) {
            $payload['certificateSecret'] = $v;
        }
        if (null !== $v = $this->cslgAtom) {
            if (!Mp4CslgAtom::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "cslgAtom" for "%s". The value "%s" is not a valid "Mp4CslgAtom".', __CLASS__, $v));
            }
            $payload['cslgAtom'] = $v;
        }
        if (null !== $v = $this->cttsVersion) {
            $payload['cttsVersion'] = $v;
        }
        if (null !== $v = $this->freeSpaceBox) {
            if (!Mp4FreeSpaceBox::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "freeSpaceBox" for "%s". The value "%s" is not a valid "Mp4FreeSpaceBox".', __CLASS__, $v));
            }
            $payload['freeSpaceBox'] = $v;
        }
        if (null !== $v = $this->moovPlacement) {
            if (!Mp4MoovPlacement::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "moovPlacement" for "%s". The value "%s" is not a valid "Mp4MoovPlacement".', __CLASS__, $v));
            }
            $payload['moovPlacement'] = $v;
        }
        if (null !== $v = $this->mp4MajorBrand) {
            $payload['mp4MajorBrand'] = $v;
        }
        if (null !== $v = $this->signingKmsKey) {
            $payload['signingKmsKey'] = $v;
        }

        return $payload;
    }
}
