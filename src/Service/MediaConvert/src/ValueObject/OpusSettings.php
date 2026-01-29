<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value OPUS.
 */
final class OpusSettings
{
    /**
     * Optional. Specify the average bitrate in bits per second. Valid values are multiples of 8000, from 32000 through
     * 192000. The default value is 96000, which we recommend for quality and bandwidth.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Specify the number of channels in this output audio track. Choosing Follow input will use the number of channels
     * found in the audio source; choosing Mono gives you 1 output channel; choosing Stereo gives you 2. In the API, valid
     * values are 0, 1, and 2.
     *
     * @var int|null
     */
    private $channels;

    /**
     * Optional. Sample rate in Hz. Valid values are 16000, 24000, and 48000. The default value is 48000.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->channels = $input['Channels'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
    }

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     * }|OpusSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
