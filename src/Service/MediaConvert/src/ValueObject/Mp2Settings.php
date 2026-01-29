<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Mp2AudioDescriptionMix;

/**
 * Required when you set Codec to the value MP2.
 */
final class Mp2Settings
{
    /**
     * Choose BROADCASTER_MIXED_AD when the input contains pre-mixed main audio + audio description (AD) as a stereo pair.
     * The value for AudioType will be set to 3, which signals to downstream systems that this stream contains "broadcaster
     * mixed AD". Note that the input received by the encoder must contain pre-mixed audio; the encoder does not perform the
     * mixing. When you choose BROADCASTER_MIXED_AD, the encoder ignores any values you provide in AudioType and
     * FollowInputAudioType. Choose NONE when the input does not contain pre-mixed audio + audio description (AD). In this
     * case, the encoder will use any values you provide for AudioType and FollowInputAudioType.
     *
     * @var Mp2AudioDescriptionMix::*|null
     */
    private $audioDescriptionMix;

    /**
     * Specify the average bitrate in bits per second.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Set Channels to specify the number of channels in this output audio track. Choosing Follow input will use the number
     * of channels found in the audio source; choosing Mono will give you 1 output channel; choosing Stereo will give you 2.
     * In the API, valid values are 0, 1, and 2.
     *
     * @var int|null
     */
    private $channels;

    /**
     * Sample rate in Hz.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * @param array{
     *   AudioDescriptionMix?: Mp2AudioDescriptionMix::*|null,
     *   Bitrate?: int|null,
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->audioDescriptionMix = $input['AudioDescriptionMix'] ?? null;
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->channels = $input['Channels'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
    }

    /**
     * @param array{
     *   AudioDescriptionMix?: Mp2AudioDescriptionMix::*|null,
     *   Bitrate?: int|null,
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     * }|Mp2Settings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Mp2AudioDescriptionMix::*|null
     */
    public function getAudioDescriptionMix(): ?string
    {
        return $this->audioDescriptionMix;
    }

    public function getBitrate(): ?int
    {
        return $this->bitrate;
    }

    public function getChannels(): ?int
    {
        return $this->channels;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->audioDescriptionMix) {
            if (!Mp2AudioDescriptionMix::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "audioDescriptionMix" for "%s". The value "%s" is not a valid "Mp2AudioDescriptionMix".', __CLASS__, $v));
            }
            $payload['audioDescriptionMix'] = $v;
        }
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->channels) {
            $payload['channels'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }

        return $payload;
    }
}
