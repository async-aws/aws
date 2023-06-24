<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Required when you set (Codec) under (AudioDescriptions)>(CodecSettings) to the value AIFF.
 */
final class AiffSettings
{
    /**
     * Specify Bit depth (BitDepth), in bits per sample, to choose the encoding quality for this audio track.
     */
    private $bitDepth;

    /**
     * Specify the number of channels in this output audio track. Valid values are 1 and even numbers up to 64. For example,
     * 1, 2, 4, 6, and so on, up to 64.
     */
    private $channels;

    /**
     * Sample rate in hz.
     */
    private $sampleRate;

    /**
     * @param array{
     *   BitDepth?: null|int,
     *   Channels?: null|int,
     *   SampleRate?: null|int,
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
     *   BitDepth?: null|int,
     *   Channels?: null|int,
     *   SampleRate?: null|int,
     * }|AiffSettings $input
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
