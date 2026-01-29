<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\MxfAfdSignaling;
use AsyncAws\MediaConvert\Enum\MxfProfile;
use AsyncAws\MediaConvert\Enum\MxfUncompressedAudioWrapping;

/**
 * These settings relate to your MXF output container.
 */
final class MxfSettings
{
    /**
     * Optional. When you have AFD signaling set up in your output video stream, use this setting to choose whether to also
     * include it in the MXF wrapper. Choose Don't copy to exclude AFD signaling from the MXF wrapper. Choose Copy from
     * video stream to copy the AFD values from the video stream for this output to the MXF wrapper. Regardless of which
     * option you choose, the AFD values remain in the video stream. Related settings: To set up your output to include or
     * exclude AFD values, see AfdSignaling, under VideoDescription. On the console, find AFD signaling under the output's
     * video encoding settings.
     *
     * @var MxfAfdSignaling::*|null
     */
    private $afdSignaling;

    /**
     * Specify the MXF profile, also called shim, for this output. To automatically select a profile according to your
     * output video codec and resolution, leave blank. For a list of codecs supported with each MXF profile, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/codecs-supported-with-each-mxf-profile.html. For more information
     * about the automatic selection behavior, see
     * https://docs.aws.amazon.com/mediaconvert/latest/ug/default-automatic-selection-of-mxf-profiles.html.
     *
     * @var MxfProfile::*|null
     */
    private $profile;

    /**
     * Choose the audio frame wrapping mode for PCM tracks in MXF outputs. AUTO (default): Uses codec-appropriate defaults -
     * BWF for H.264/AVC, AES3 for MPEG2/XDCAM. AES3: Use AES3 frame wrapping with SMPTE-compliant descriptors. This setting
     * only takes effect when the MXF profile is OP1a.
     *
     * @var MxfUncompressedAudioWrapping::*|null
     */
    private $uncompressedAudioWrapping;

    /**
     * Specify the XAVC profile settings for MXF outputs when you set your MXF profile to XAVC.
     *
     * @var MxfXavcProfileSettings|null
     */
    private $xavcProfileSettings;

    /**
     * @param array{
     *   AfdSignaling?: MxfAfdSignaling::*|null,
     *   Profile?: MxfProfile::*|null,
     *   UncompressedAudioWrapping?: MxfUncompressedAudioWrapping::*|null,
     *   XavcProfileSettings?: MxfXavcProfileSettings|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->afdSignaling = $input['AfdSignaling'] ?? null;
        $this->profile = $input['Profile'] ?? null;
        $this->uncompressedAudioWrapping = $input['UncompressedAudioWrapping'] ?? null;
        $this->xavcProfileSettings = isset($input['XavcProfileSettings']) ? MxfXavcProfileSettings::create($input['XavcProfileSettings']) : null;
    }

    /**
     * @param array{
     *   AfdSignaling?: MxfAfdSignaling::*|null,
     *   Profile?: MxfProfile::*|null,
     *   UncompressedAudioWrapping?: MxfUncompressedAudioWrapping::*|null,
     *   XavcProfileSettings?: MxfXavcProfileSettings|array|null,
     * }|MxfSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MxfAfdSignaling::*|null
     */
    public function getAfdSignaling(): ?string
    {
        return $this->afdSignaling;
    }

    /**
     * @return MxfProfile::*|null
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * @return MxfUncompressedAudioWrapping::*|null
     */
    public function getUncompressedAudioWrapping(): ?string
    {
        return $this->uncompressedAudioWrapping;
    }

    public function getXavcProfileSettings(): ?MxfXavcProfileSettings
    {
        return $this->xavcProfileSettings;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->afdSignaling) {
            if (!MxfAfdSignaling::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "afdSignaling" for "%s". The value "%s" is not a valid "MxfAfdSignaling".', __CLASS__, $v));
            }
            $payload['afdSignaling'] = $v;
        }
        if (null !== $v = $this->profile) {
            if (!MxfProfile::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "profile" for "%s". The value "%s" is not a valid "MxfProfile".', __CLASS__, $v));
            }
            $payload['profile'] = $v;
        }
        if (null !== $v = $this->uncompressedAudioWrapping) {
            if (!MxfUncompressedAudioWrapping::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "uncompressedAudioWrapping" for "%s". The value "%s" is not a valid "MxfUncompressedAudioWrapping".', __CLASS__, $v));
            }
            $payload['uncompressedAudioWrapping'] = $v;
        }
        if (null !== $v = $this->xavcProfileSettings) {
            $payload['xavcProfileSettings'] = $v->requestBody();
        }

        return $payload;
    }
}
