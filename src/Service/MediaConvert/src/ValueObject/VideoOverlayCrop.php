<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\VideoOverlayUnit;

/**
 * Specify a rectangle of content to crop and use from your video overlay's input video. When you do, MediaConvert uses
 * the cropped dimensions that you specify under X offset, Y offset, Width, and Height.
 */
final class VideoOverlayCrop
{
    /**
     * Specify the height of the video overlay cropping rectangle. To use the same height as your overlay input video: Keep
     * blank, or enter 0. To specify a different height for the cropping rectangle: Enter an integer representing the Unit
     * type that you choose, either Pixels or Percentage. For example, when you enter 100 and choose Pixels, the cropping
     * rectangle will be 100 pixels high. When you enter 10, choose Percentage, and your overlay input video is 1920x1080,
     * the cropping rectangle will be 108 pixels high.
     *
     * @var int|null
     */
    private $height;

    /**
     * Specify the Unit type to use when you enter a value for X position, Y position, Width, or Height. You can choose
     * Pixels or Percentage. Leave blank to use the default value, Pixels.
     *
     * @var VideoOverlayUnit::*|null
     */
    private $unit;

    /**
     * Specify the width of the video overlay cropping rectangle. To use the same width as your overlay input video: Keep
     * blank, or enter 0. To specify a different width for the cropping rectangle: Enter an integer representing the Unit
     * type that you choose, either Pixels or Percentage. For example, when you enter 100 and choose Pixels, the cropping
     * rectangle will be 100 pixels wide. When you enter 10, choose Percentage, and your overlay input video is 1920x1080,
     * the cropping rectangle will be 192 pixels wide.
     *
     * @var int|null
     */
    private $width;

    /**
     * Specify the distance between the cropping rectangle and the left edge of your overlay video's frame. To position the
     * cropping rectangle along the left edge: Keep blank, or enter 0. To position the cropping rectangle to the right,
     * relative to the left edge of your overlay video's frame: Enter an integer representing the Unit type that you choose,
     * either Pixels or Percentage. For example, when you enter 10 and choose Pixels, the cropping rectangle will be
     * positioned 10 pixels from the left edge of the overlay video's frame. When you enter 10, choose Percentage, and your
     * overlay input video is 1920x1080, the cropping rectangle will be positioned 192 pixels from the left edge of the
     * overlay video's frame.
     *
     * @var int|null
     */
    private $x;

    /**
     * Specify the distance between the cropping rectangle and the top edge of your overlay video's frame. To position the
     * cropping rectangle along the top edge: Keep blank, or enter 0. To position the cropping rectangle down, relative to
     * the top edge of your overlay video's frame: Enter an integer representing the Unit type that you choose, either
     * Pixels or Percentage. For example, when you enter 10 and choose Pixels, the cropping rectangle will be positioned 10
     * pixels from the top edge of the overlay video's frame. When you enter 10, choose Percentage, and your overlay input
     * video is 1920x1080, the cropping rectangle will be positioned 108 pixels from the top edge of the overlay video's
     * frame.
     *
     * @var int|null
     */
    private $y;

    /**
     * @param array{
     *   Height?: int|null,
     *   Unit?: VideoOverlayUnit::*|null,
     *   Width?: int|null,
     *   X?: int|null,
     *   Y?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->height = $input['Height'] ?? null;
        $this->unit = $input['Unit'] ?? null;
        $this->width = $input['Width'] ?? null;
        $this->x = $input['X'] ?? null;
        $this->y = $input['Y'] ?? null;
    }

    /**
     * @param array{
     *   Height?: int|null,
     *   Unit?: VideoOverlayUnit::*|null,
     *   Width?: int|null,
     *   X?: int|null,
     *   Y?: int|null,
     * }|VideoOverlayCrop $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return VideoOverlayUnit::*|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getX(): ?int
    {
        return $this->x;
    }

    public function getY(): ?int
    {
        return $this->y;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->height) {
            $payload['height'] = $v;
        }
        if (null !== $v = $this->unit) {
            if (!VideoOverlayUnit::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "unit" for "%s". The value "%s" is not a valid "VideoOverlayUnit".', __CLASS__, $v));
            }
            $payload['unit'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
        }
        if (null !== $v = $this->x) {
            $payload['x'] = $v;
        }
        if (null !== $v = $this->y) {
            $payload['y'] = $v;
        }

        return $payload;
    }
}
