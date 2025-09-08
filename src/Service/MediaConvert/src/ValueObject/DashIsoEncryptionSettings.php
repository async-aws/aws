<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\DashIsoPlaybackDeviceCompatibility;

/**
 * Specifies DRM settings for DASH outputs.
 */
final class DashIsoEncryptionSettings
{
    /**
     * This setting can improve the compatibility of your output with video players on obsolete devices. It applies only to
     * DASH H.264 outputs with DRM encryption. Choose Unencrypted SEI only to correct problems with playback on older
     * devices. Otherwise, keep the default setting CENC v1. If you choose Unencrypted SEI, for that output, the service
     * will exclude the access unit delimiter and will leave the SEI NAL units unencrypted.
     *
     * @var DashIsoPlaybackDeviceCompatibility::*|null
     */
    private $playbackDeviceCompatibility;

    /**
     * If your output group type is HLS, DASH, or Microsoft Smooth, use these settings when doing DRM encryption with a
     * SPEKE-compliant key provider. If your output group type is CMAF, use the SpekeKeyProviderCmaf settings instead.
     *
     * @var SpekeKeyProvider|null
     */
    private $spekeKeyProvider;

    /**
     * @param array{
     *   PlaybackDeviceCompatibility?: DashIsoPlaybackDeviceCompatibility::*|null,
     *   SpekeKeyProvider?: SpekeKeyProvider|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->playbackDeviceCompatibility = $input['PlaybackDeviceCompatibility'] ?? null;
        $this->spekeKeyProvider = isset($input['SpekeKeyProvider']) ? SpekeKeyProvider::create($input['SpekeKeyProvider']) : null;
    }

    /**
     * @param array{
     *   PlaybackDeviceCompatibility?: DashIsoPlaybackDeviceCompatibility::*|null,
     *   SpekeKeyProvider?: SpekeKeyProvider|array|null,
     * }|DashIsoEncryptionSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DashIsoPlaybackDeviceCompatibility::*|null
     */
    public function getPlaybackDeviceCompatibility(): ?string
    {
        return $this->playbackDeviceCompatibility;
    }

    public function getSpekeKeyProvider(): ?SpekeKeyProvider
    {
        return $this->spekeKeyProvider;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->playbackDeviceCompatibility) {
            if (!DashIsoPlaybackDeviceCompatibility::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "playbackDeviceCompatibility" for "%s". The value "%s" is not a valid "DashIsoPlaybackDeviceCompatibility".', __CLASS__, $v));
            }
            $payload['playbackDeviceCompatibility'] = $v;
        }
        if (null !== $v = $this->spekeKeyProvider) {
            $payload['spekeKeyProvider'] = $v->requestBody();
        }

        return $payload;
    }
}
