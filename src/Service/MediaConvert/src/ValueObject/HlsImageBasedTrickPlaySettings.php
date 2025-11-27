<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\HlsIntervalCadence;

/**
 * Tile and thumbnail settings applicable when imageBasedTrickPlay is ADVANCED.
 */
final class HlsImageBasedTrickPlaySettings
{
    /**
     * The cadence MediaConvert follows for generating thumbnails. If set to FOLLOW_IFRAME, MediaConvert generates
     * thumbnails for each IDR frame in the output (matching the GOP cadence). If set to FOLLOW_CUSTOM, MediaConvert
     * generates thumbnails according to the interval you specify in thumbnailInterval.
     *
     * @var HlsIntervalCadence::*|null
     */
    private $intervalCadence;

    /**
     * Height of each thumbnail within each tile image, in pixels. Leave blank to maintain aspect ratio with thumbnail
     * width. If following the aspect ratio would lead to a total tile height greater than 4096, then the job will be
     * rejected. Must be divisible by 2.
     *
     * @var int|null
     */
    private $thumbnailHeight;

    /**
     * Enter the interval, in seconds, that MediaConvert uses to generate thumbnails. If the interval you enter doesn't
     * align with the output frame rate, MediaConvert automatically rounds the interval to align with the output frame rate.
     * For example, if the output frame rate is 29.97 frames per second and you enter 5, MediaConvert uses a 150 frame
     * interval to generate thumbnails.
     *
     * @var float|null
     */
    private $thumbnailInterval;

    /**
     * Width of each thumbnail within each tile image, in pixels. Default is 312. Must be divisible by 8.
     *
     * @var int|null
     */
    private $thumbnailWidth;

    /**
     * Number of thumbnails in each column of a tile image. Set a value between 2 and 2048. Must be divisible by 2.
     *
     * @var int|null
     */
    private $tileHeight;

    /**
     * Number of thumbnails in each row of a tile image. Set a value between 1 and 512.
     *
     * @var int|null
     */
    private $tileWidth;

    /**
     * @param array{
     *   IntervalCadence?: HlsIntervalCadence::*|null,
     *   ThumbnailHeight?: int|null,
     *   ThumbnailInterval?: float|null,
     *   ThumbnailWidth?: int|null,
     *   TileHeight?: int|null,
     *   TileWidth?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->intervalCadence = $input['IntervalCadence'] ?? null;
        $this->thumbnailHeight = $input['ThumbnailHeight'] ?? null;
        $this->thumbnailInterval = $input['ThumbnailInterval'] ?? null;
        $this->thumbnailWidth = $input['ThumbnailWidth'] ?? null;
        $this->tileHeight = $input['TileHeight'] ?? null;
        $this->tileWidth = $input['TileWidth'] ?? null;
    }

    /**
     * @param array{
     *   IntervalCadence?: HlsIntervalCadence::*|null,
     *   ThumbnailHeight?: int|null,
     *   ThumbnailInterval?: float|null,
     *   ThumbnailWidth?: int|null,
     *   TileHeight?: int|null,
     *   TileWidth?: int|null,
     * }|HlsImageBasedTrickPlaySettings $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return HlsIntervalCadence::*|null
     */
    public function getIntervalCadence(): ?string
    {
        return $this->intervalCadence;
    }

    public function getThumbnailHeight(): ?int
    {
        return $this->thumbnailHeight;
    }

    public function getThumbnailInterval(): ?float
    {
        return $this->thumbnailInterval;
    }

    public function getThumbnailWidth(): ?int
    {
        return $this->thumbnailWidth;
    }

    public function getTileHeight(): ?int
    {
        return $this->tileHeight;
    }

    public function getTileWidth(): ?int
    {
        return $this->tileWidth;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->intervalCadence) {
            if (!HlsIntervalCadence::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "intervalCadence" for "%s". The value "%s" is not a valid "HlsIntervalCadence".', __CLASS__, $v));
            }
            $payload['intervalCadence'] = $v;
        }
        if (null !== $v = $this->thumbnailHeight) {
            $payload['thumbnailHeight'] = $v;
        }
        if (null !== $v = $this->thumbnailInterval) {
            $payload['thumbnailInterval'] = $v;
        }
        if (null !== $v = $this->thumbnailWidth) {
            $payload['thumbnailWidth'] = $v;
        }
        if (null !== $v = $this->tileHeight) {
            $payload['tileHeight'] = $v;
        }
        if (null !== $v = $this->tileWidth) {
            $payload['tileWidth'] = $v;
        }

        return $payload;
    }
}
