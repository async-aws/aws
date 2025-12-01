<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\VideoOverlayUnit;

/**
 * position of video overlay.
 */
final class VideoOverlayPosition
{
    /**
     * To scale your video overlay to the same height as the base input video: Leave blank. To scale the height of your
     * video overlay to a different height: Enter an integer representing the Unit type that you choose, either Pixels or
     * Percentage. For example, when you enter 360 and choose Pixels, your video overlay will be rendered with a height of
     * 360. When you enter 50, choose Percentage, and your overlay's source has a height of 1080, your video overlay will be
     * rendered with a height of 540. To scale your overlay to a specific height while automatically maintaining its
     * original aspect ratio, enter a value for Height and leave Width blank.
     *
     * @var int|null
     */
    private $height;

    /**
     * Use Opacity to specify how much of the underlying video shows through the overlay video. 0 is transparent and 100 is
     * fully opaque. Default is 100.
     *
     * @var int|null
     */
    private $opacity;

    /**
     * Specify the Unit type to use when you enter a value for X position, Y position, Width, or Height. You can choose
     * Pixels or Percentage. Leave blank to use the default value, Pixels.
     *
     * @var VideoOverlayUnit::*|null
     */
    private $unit;

    /**
     * To scale your video overlay to the same width as the base input video: Leave blank. To scale the width of your video
     * overlay to a different width: Enter an integer representing the Unit type that you choose, either Pixels or
     * Percentage. For example, when you enter 640 and choose Pixels, your video overlay will scale to a height of 640
     * pixels. When you enter 50, choose Percentage, and your overlay's source has a width of 1920, your video overlay will
     * scale to a width of 960. To scale your overlay to a specific width while automatically maintaining its original
     * aspect ratio, enter a value for Width and leave Height blank.
     *
     * @var int|null
     */
    private $width;

    /**
     * To position the left edge of your video overlay along the left edge of the base input video's frame: Keep blank, or
     * enter 0. To position the left edge of your video overlay to the right, relative to the left edge of the base input
     * video's frame: Enter an integer representing the Unit type that you choose, either Pixels or Percentage. For example,
     * when you enter 10 and choose Pixels, your video overlay will be positioned 10 pixels from the left edge of the base
     * input video's frame. When you enter 10, choose Percentage, and your base input video is 1920x1080, your video overlay
     * will be positioned 192 pixels from the left edge of the base input video's frame.
     *
     * @var int|null
     */
    private $xPosition;

    /**
     * To position the top edge of your video overlay along the top edge of the base input video's frame: Keep blank, or
     * enter 0. To position the top edge of your video overlay down, relative to the top edge of the base input video's
     * frame: Enter an integer representing the Unit type that you choose, either Pixels or Percentage. For example, when
     * you enter 10 and choose Pixels, your video overlay will be positioned 10 pixels from the top edge of the base input
     * video's frame. When you enter 10, choose Percentage, and your underlying video is 1920x1080, your video overlay will
     * be positioned 108 pixels from the top edge of the base input video's frame.
     *
     * @var int|null
     */
    private $yPosition;

    /**
     * @param array{
     *   Height?: int|null,
     *   Opacity?: int|null,
     *   Unit?: VideoOverlayUnit::*|null,
     *   Width?: int|null,
     *   XPosition?: int|null,
     *   YPosition?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->height = $input['Height'] ?? null;
        $this->opacity = $input['Opacity'] ?? null;
        $this->unit = $input['Unit'] ?? null;
        $this->width = $input['Width'] ?? null;
        $this->xPosition = $input['XPosition'] ?? null;
        $this->yPosition = $input['YPosition'] ?? null;
    }

    /**
     * @param array{
     *   Height?: int|null,
     *   Opacity?: int|null,
     *   Unit?: VideoOverlayUnit::*|null,
     *   Width?: int|null,
     *   XPosition?: int|null,
     *   YPosition?: int|null,
     * }|VideoOverlayPosition $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getOpacity(): ?int
    {
        return $this->opacity;
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

    public function getXPosition(): ?int
    {
        return $this->xPosition;
    }

    public function getYPosition(): ?int
    {
        return $this->yPosition;
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
        if (null !== $v = $this->opacity) {
            $payload['opacity'] = $v;
        }
        if (null !== $v = $this->unit) {
            if (!VideoOverlayUnit::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "unit" for "%s". The value "%s" is not a valid "VideoOverlayUnit".', __CLASS__, $v));
            }
            $payload['unit'] = $v;
        }
        if (null !== $v = $this->width) {
            $payload['width'] = $v;
        }
        if (null !== $v = $this->xPosition) {
            $payload['xPosition'] = $v;
        }
        if (null !== $v = $this->yPosition) {
            $payload['yPosition'] = $v;
        }

        return $payload;
    }
}
