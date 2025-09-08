<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * When you include Video generator, MediaConvert creates a video input with black frames. Use this setting if you do
 * not have a video input or if you want to add black video frames before, or after, other inputs. You can specify Video
 * generator, or you can specify an Input file, but you cannot specify both. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/video-generator.html.
 */
final class InputVideoGenerator
{
    /**
     * Specify the number of audio channels to include in your video generator input. MediaConvert creates these audio
     * channels as silent audio within a single audio track. Enter an integer from 1 to 32.
     *
     * @var int|null
     */
    private $channels;

    /**
     * Specify the duration, in milliseconds, for your video generator input.
     * Enter an integer from 50 to 86400000.
     *
     * @var int|null
     */
    private $duration;

    /**
     * Specify the denominator of the fraction that represents the frame rate for your video generator input. When you do,
     * you must also specify a value for Frame rate numerator. MediaConvert uses a default frame rate of 29.97 when you
     * leave Frame rate numerator and Frame rate denominator blank.
     *
     * @var int|null
     */
    private $framerateDenominator;

    /**
     * Specify the numerator of the fraction that represents the frame rate for your video generator input. When you do, you
     * must also specify a value for Frame rate denominator. MediaConvert uses a default frame rate of 29.97 when you leave
     * Frame rate numerator and Frame rate denominator blank.
     *
     * @var int|null
     */
    private $framerateNumerator;

    /**
     * Specify the audio sample rate, in Hz, for the silent audio in your video generator input.
     * Enter an integer from 32000 to 48000.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * @param array{
     *   Channels?: int|null,
     *   Duration?: int|null,
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   SampleRate?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->channels = $input['Channels'] ?? null;
        $this->duration = $input['Duration'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
    }

    /**
     * @param array{
     *   Channels?: int|null,
     *   Duration?: int|null,
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   SampleRate?: int|null,
     * }|InputVideoGenerator $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getChannels(): ?int
    {
        return $this->channels;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getFramerateDenominator(): ?int
    {
        return $this->framerateDenominator;
    }

    public function getFramerateNumerator(): ?int
    {
        return $this->framerateNumerator;
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
        if (null !== $v = $this->channels) {
            $payload['channels'] = $v;
        }
        if (null !== $v = $this->duration) {
            $payload['duration'] = $v;
        }
        if (null !== $v = $this->framerateDenominator) {
            $payload['framerateDenominator'] = $v;
        }
        if (null !== $v = $this->framerateNumerator) {
            $payload['framerateNumerator'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }

        return $payload;
    }
}
