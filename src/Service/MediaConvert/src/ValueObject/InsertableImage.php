<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * These settings apply to a specific graphic overlay. You can include multiple overlays in your job.
 */
final class InsertableImage
{
    /**
     * Specify the time, in milliseconds, for the image to remain on the output video. This duration includes fade-in time
     * but not fade-out time.
     *
     * @var int|null
     */
    private $duration;

    /**
     * Specify the length of time, in milliseconds, between the Start time that you specify for the image insertion and the
     * time that the image appears at full opacity. Full opacity is the level that you specify for the opacity setting. If
     * you don't specify a value for Fade-in, the image will appear abruptly at the overlay start time.
     *
     * @var int|null
     */
    private $fadeIn;

    /**
     * Specify the length of time, in milliseconds, between the end of the time that you have specified for the image
     * overlay Duration and when the overlaid image has faded to total transparency. If you don't specify a value for
     * Fade-out, the image will disappear abruptly at the end of the inserted image duration.
     *
     * @var int|null
     */
    private $fadeOut;

    /**
     * Specify the height of the inserted image in pixels. If you specify a value that's larger than the video resolution
     * height, the service will crop your overlaid image to fit. To use the native height of the image, keep this setting
     * blank.
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
    private $imageInserterInput;

    /**
     * Specify the distance, in pixels, between the inserted image and the left edge of the video frame. Required for any
     * image overlay that you specify.
     *
     * @var int|null
     */
    private $imageX;

    /**
     * Specify the distance, in pixels, between the overlaid image and the top edge of the video frame. Required for any
     * image overlay that you specify.
     *
     * @var int|null
     */
    private $imageY;

    /**
     * Specify how overlapping inserted images appear. Images with higher values for Layer appear on top of images with
     * lower values for Layer.
     *
     * @var int|null
     */
    private $layer;

    /**
     * Use Opacity to specify how much of the underlying video shows through the inserted image. 0 is transparent and 100 is
     * fully opaque. Default is 50.
     *
     * @var int|null
     */
    private $opacity;

    /**
     * Specify the timecode of the frame that you want the overlay to first appear on. This must be in timecode (HH:MM:SS:FF
     * or HH:MM:SS;FF) format. Remember to take into account your timecode source settings.
     *
     * @var string|null
     */
    private $startTime;

    /**
     * Specify the width of the inserted image in pixels. If you specify a value that's larger than the video resolution
     * width, the service will crop your overlaid image to fit. To use the native width of the image, keep this setting
     * blank.
     *
     * @var int|null
     */
    private $width;

    /**
     * @param array{
     *   Duration?: int|null,
     *   FadeIn?: int|null,
     *   FadeOut?: int|null,
     *   Height?: int|null,
     *   ImageInserterInput?: string|null,
     *   ImageX?: int|null,
     *   ImageY?: int|null,
     *   Layer?: int|null,
     *   Opacity?: int|null,
     *   StartTime?: string|null,
     *   Width?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->duration = $input['Duration'] ?? null;
        $this->fadeIn = $input['FadeIn'] ?? null;
        $this->fadeOut = $input['FadeOut'] ?? null;
        $this->height = $input['Height'] ?? null;
        $this->imageInserterInput = $input['ImageInserterInput'] ?? null;
        $this->imageX = $input['ImageX'] ?? null;
        $this->imageY = $input['ImageY'] ?? null;
        $this->layer = $input['Layer'] ?? null;
        $this->opacity = $input['Opacity'] ?? null;
        $this->startTime = $input['StartTime'] ?? null;
        $this->width = $input['Width'] ?? null;
    }

    /**
     * @param array{
     *   Duration?: int|null,
     *   FadeIn?: int|null,
     *   FadeOut?: int|null,
     *   Height?: int|null,
     *   ImageInserterInput?: string|null,
     *   ImageX?: int|null,
     *   ImageY?: int|null,
     *   Layer?: int|null,
     *   Opacity?: int|null,
     *   StartTime?: string|null,
     *   Width?: int|null,
     * }|InsertableImage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function getFadeIn(): ?int
    {
        return $this->fadeIn;
    }

    public function getFadeOut(): ?int
    {
        return $this->fadeOut;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getImageInserterInput(): ?string
    {
        return $this->imageInserterInput;
    }

    public function getImageX(): ?int
    {
        return $this->imageX;
    }

    public function getImageY(): ?int
    {
        return $this->imageY;
    }

    public function getLayer(): ?int
    {
        return $this->layer;
    }

    public function getOpacity(): ?int
    {
        return $this->opacity;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
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
        if (null !== $v = $this->duration) {
            $payload['duration'] = $v;
        }
        if (null !== $v = $this->fadeIn) {
            $payload['fadeIn'] = $v;
        }
        if (null !== $v = $this->fadeOut) {
            $payload['fadeOut'] = $v;
        }
        if (null !== $v = $this->height) {
            $payload['height'] = $v;
        }
        if (null !== $v = $this->imageInserterInput) {
            $payload['imageInserterInput'] = $v;
        }
        if (null !== $v = $this->imageX) {
            $payload['imageX'] = $v;
        }
        if (null !== $v = $this->imageY) {
            $payload['imageY'] = $v;
        }
        if (null !== $v = $this->layer) {
            $payload['layer'] = $v;
        }
        if (null !== $v = $this->opacity) {
            $payload['opacity'] = $v;
        }
        if (null !== $v = $this->startTime) {
            $payload['startTime'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
        }

        return $payload;
    }
}
