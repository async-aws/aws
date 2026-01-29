<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value Vorbis.
 */
final class VorbisSettings
{
    /**
     * Optional. Specify the number of channels in this output audio track. Choosing Follow input will use the number of
     * channels found in the audio source; choosing Mono on the console gives you 1 output channel; choosing Stereo gives
     * you 2. In the API, valid values are 0, 1, and 2. The default value is 2.
     *
     * @var int|null
     */
    private $channels;

    /**
     * Optional. Specify the audio sample rate in Hz. Valid values are 22050, 32000, 44100, and 48000. The default value is
     * 48000.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * Optional. Specify the variable audio quality of this Vorbis output from -1 (lowest quality, ~45 kbit/s) to 10
     * (highest quality, ~500 kbit/s). The default value is 4 (~128 kbit/s). Values 5 and 6 are approximately 160 and 192
     * kbit/s, respectively.
     *
     * @var int|null
     */
    private $vbrQuality;

    /**
     * @param array{
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     *   VbrQuality?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->channels = $input['Channels'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
        $this->vbrQuality = $input['VbrQuality'] ?? null;
    }

    /**
     * @param array{
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     *   VbrQuality?: int|null,
     * }|VorbisSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getChannels(): ?int
    {
        return $this->channels;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    public function getVbrQuality(): ?int
    {
        return $this->vbrQuality;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->channels) {
            $payload['channels'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }
        if (null !== $v = $this->vbrQuality) {
            $payload['vbrQuality'] = $v;
        }

        return $payload;
    }
}
