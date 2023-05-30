<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\WavFormat;

/**
 * Required when you set (Codec) under (AudioDescriptions)>(CodecSettings) to the value WAV.
 */
final class WavSettings
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
     * The service defaults to using RIFF for WAV outputs. If your output audio is likely to exceed 4 GB in file size, or if
     * you otherwise need the extended support of the RF64 format, set your output WAV file format to RF64.
     */
    private $format;

    /**
     * Sample rate in Hz.
     */
    private $sampleRate;

    /**
     * @param array{
     *   BitDepth?: null|int,
     *   Channels?: null|int,
     *   Format?: null|WavFormat::*,
     *   SampleRate?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitDepth = $input['BitDepth'] ?? null;
        $this->channels = $input['Channels'] ?? null;
        $this->format = $input['Format'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
    }

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

    /**
     * @return WavFormat::*|null
     */
    public function getFormat(): ?string
    {
        return $this->format;
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
        if (null !== $v = $this->format) {
            if (!WavFormat::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "format" for "%s". The value "%s" is not a valid "WavFormat".', __CLASS__, $v));
            }
            $payload['format'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }

        return $payload;
    }
}
