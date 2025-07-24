<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\WavFormat;

/**
 * Required when you set Codec to the value WAV.
 */
final class WavSettings
{
    /**
     * Specify Bit depth, in bits per sample, to choose the encoding quality for this audio track.
     *
     * @var int|null
     */
    private $bitDepth;

    /**
     * Specify the number of channels in this output audio track. Valid values are 1 and even numbers up to 64. For example,
     * 1, 2, 4, 6, and so on, up to 64.
     *
     * @var int|null
     */
    private $channels;

    /**
     * Specify the file format for your wave audio output. To use a RIFF wave format: Keep the default value, RIFF. If your
     * output audio is likely to exceed 4GB in file size, or if you otherwise need the extended support of the RF64 format:
     * Choose RF64. If your player only supports the extensible wave format: Choose Extensible.
     *
     * @var WavFormat::*|string|null
     */
    private $format;

    /**
     * Sample rate in Hz.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * @param array{
     *   BitDepth?: null|int,
     *   Channels?: null|int,
     *   Format?: null|WavFormat::*|string,
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

    /**
     * @param array{
     *   BitDepth?: null|int,
     *   Channels?: null|int,
     *   Format?: null|WavFormat::*|string,
     *   SampleRate?: null|int,
     * }|WavSettings $input
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

    /**
     * @return WavFormat::*|string|null
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
                throw new InvalidArgument(\sprintf('Invalid parameter "format" for "%s". The value "%s" is not a valid "WavFormat".', __CLASS__, $v));
            }
            $payload['format'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }

        return $payload;
    }
}
