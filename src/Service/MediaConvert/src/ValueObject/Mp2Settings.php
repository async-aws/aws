<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Required when you set (Codec) under (AudioDescriptions)>(CodecSettings) to the value MP2.
 */
final class Mp2Settings
{
    /**
     * Specify the average bitrate in bits per second.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Set Channels to specify the number of channels in this output audio track. Choosing Mono in the console will give you
     * 1 output channel; choosing Stereo will give you 2. In the API, valid values are 1 and 2.
     *
     * @var int|null
     */
    private $channels;

    /**
     * Sample rate in hz.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * @param array{
     *   Bitrate?: null|int,
     *   Channels?: null|int,
     *   SampleRate?: null|int,
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
     *   Bitrate?: null|int,
     *   Channels?: null|int,
     *   SampleRate?: null|int,
     * }|Mp2Settings $input
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
