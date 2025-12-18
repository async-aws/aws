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
     * Enter an integer from 1 to 86400000.
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
     * Specify the height, in pixels, for your video generator input. This is useful for positioning when you include one or
     * more video overlays for this input. To use the default resolution 540x360: Leave both width and height blank. To
     * specify a height: Enter an even integer from 32 to 8192. When you do, you must also specify a value for width.
     *
     * @var int|null
     */
    private $height;

    /**
     * Specify the HTTP, HTTPS, or Amazon S3 location of the image that you want to overlay on the video. Use a PNG or TGA
     * file.
     *
     * @var string|null
     */
    private $imageInput;

    /**
     * Specify the audio sample rate, in Hz, for the silent audio in your video generator input.
     * Enter an integer from 32000 to 48000.
     *
     * @var int|null
     */
    private $sampleRate;

    /**
     * Specify the width, in pixels, for your video generator input. This is useful for positioning when you include one or
     * more video overlays for this input. To use the default resolution 540x360: Leave both width and height blank. To
     * specify a width: Enter an even integer from 32 to 8192. When you do, you must also specify a value for height.
     *
     * @var int|null
     */
    private $width;

    /**
     * @param array{
     *   Channels?: int|null,
     *   Duration?: int|null,
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   Height?: int|null,
     *   ImageInput?: string|null,
     *   SampleRate?: int|null,
     *   Width?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->channels = $input['Channels'] ?? null;
        $this->duration = $input['Duration'] ?? null;
        $this->framerateDenominator = $input['FramerateDenominator'] ?? null;
        $this->framerateNumerator = $input['FramerateNumerator'] ?? null;
        $this->height = $input['Height'] ?? null;
        $this->imageInput = $input['ImageInput'] ?? null;
        $this->sampleRate = $input['SampleRate'] ?? null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   Channels?: int|null,
     *   Duration?: int|null,
     *   FramerateDenominator?: int|null,
     *   FramerateNumerator?: int|null,
     *   Height?: int|null,
     *   ImageInput?: string|null,
     *   SampleRate?: int|null,
     *   Width?: int|null,
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

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getImageInput(): ?string
    {
        return $this->imageInput;
    }

    public function getSampleRate(): ?int
    {
        return $this->sampleRate;
    }

    public function getWidth(): ?int
    {
        return $this->width;
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
        if (null !== $v = $this->height) {
            $payload['height'] = $v;
        }
        if (null !== $v = $this->imageInput) {
            $payload['imageInput'] = $v;
        }
        if (null !== $v = $this->sampleRate) {
            $payload['sampleRate'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
        }

        return $payload;
    }
}
