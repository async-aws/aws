<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\Mp3RateControlMode;

/**
 * Required when you set Codec, under AudioDescriptions>CodecSettings, to the value MP3.
 */
final class Mp3Settings
{
    /**
     * Specify the average bitrate in bits per second.
     *
     * @var int|null
     */
    private $bitrate;

    /**
     * Specify the number of channels in this output audio track. Choosing Mono gives you 1 output channel; choosing Stereo
     * gives you 2. In the API, valid values are 1 and 2.
     *
     * @var int|null
     */
    private $channels;

    /**
     * Specify whether the service encodes this MP3 audio output with a constant bitrate (CBR) or a variable bitrate (VBR).
     *
     * @var Mp3RateControlMode::*|null
     */
    private $rateControlMode;

    /**
     * Sample rate in Hz.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * Required when you set Bitrate control mode to VBR. Specify the audio quality of this MP3 output from 0 (highest
     * quality) to 9 (lowest quality).
     *
     * @var int|null
     */
    private $vbrQuality;

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   Channels?: int|null,
     *   RateControlMode?: Mp3RateControlMode::*|null,
     *   SampleRate?: int|null,
     *   VbrQuality?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bitrate = $input['Bitrate'] ?? null;
        $this->channels = $input['Channels'] ?? null;
        $this->rateControlMode = $input['RateControlMode'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
        $this->vbrQuality = $input['VbrQuality'] ?? null;
    }

    /**
     * @param array{
     *   Bitrate?: int|null,
     *   Channels?: int|null,
     *   RateControlMode?: Mp3RateControlMode::*|null,
     *   SampleRate?: int|null,
     *   VbrQuality?: int|null,
     * }|Mp3Settings $input
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

    /**
     * @return Mp3RateControlMode::*|null
     */
    public function getRateControlMode(): ?string
    {
        return $this->rateControlMode;
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
        if (null !== $v = $this->bitrate) {
            $payload['bitrate'] = $v;
        }
        if (null !== $v = $this->channels) {
            $payload['channels'] = $v;
        }
        if (null !== $v = $this->rateControlMode) {
            if (!Mp3RateControlMode::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "rateControlMode" for "%s". The value "%s" is not a valid "Mp3RateControlMode".', __CLASS__, $v));
            }
            $payload['rateControlMode'] = $v;
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
