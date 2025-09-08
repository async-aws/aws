<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value FLAC.
 */
final class FlacSettings
{
    /**
     * Specify Bit depth (BitDepth), in bits per sample, to choose the encoding quality for this audio track.
     *
     * @var int|null
     */
    private $bitDepth;

    /**
     * Specify the number of channels in this output audio track. Choosing Mono on the console gives you 1 output channel;
     * choosing Stereo gives you 2. In the API, valid values are between 1 and 8.
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
     *   BitDepth?: int|null,
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitDepth = $input['BitDepth'] ?? null;
        $this->channels = $input['Channels'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
    }

    /**
     * @param array{
     *   BitDepth?: int|null,
     *   Channels?: int|null,
     *   SampleRate?: int|null,
     * }|FlacSettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBitDepth(): ?int
    {
        return $this->bitDepth;
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
        if (null !== $v = $this->bitDepth) {
            $payload['bitDepth'] = $v;
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
