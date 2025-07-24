<?php

namespace AsyncAws\MediaConvert\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\MediaConvert\Enum\TimecodeBurninPosition;

/**
 * Settings for burning the output timecode and specified prefix into the output.
 */
final class TimecodeBurnin
{
    /**
     * Use Font size to set the font size of any burned-in timecode. Valid values are 10, 16, 32, 48.
     *
     * @var int|null
     */
    private $fontSize;

    /**
     * Use Position under Timecode burn-in to specify the location the burned-in timecode on output video.
     *
     * @var TimecodeBurninPosition::*|string|null
     */
    private $position;

    /**
     * Use Prefix to place ASCII characters before any burned-in timecode. For example, a prefix of "EZ-" will result in the
     * timecode "EZ-00:00:00:00". Provide either the characters themselves or the ASCII code equivalents. The supported
     * range of characters is 0x20 through 0x7e. This includes letters, numbers, and all special characters represented on a
     * standard English keyboard.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * @param array{
     *   FontSize?: null|int,
     *   Position?: null|TimecodeBurninPosition::*|string,
     *   Prefix?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->fontSize = $input['FontSize'] ?? null;
        $this->position = $input['Position'] ?? null;
        $this->prefix = $input['Prefix'] ?? null;
    }

    /**
     * @param array{
     *   FontSize?: null|int,
     *   Position?: null|TimecodeBurninPosition::*|string,
     *   Prefix?: null|string,
     * }|TimecodeBurnin $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getFontSize(): ?int
    {
        return $this->fontSize;
    }

    /**
     * @return TimecodeBurninPosition::*|string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->fontSize) {
            $payload['fontSize'] = $v;
        }
        if (null !== $v = $this->position) {
            if (!TimecodeBurninPosition::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "position" for "%s". The value "%s" is not a valid "TimecodeBurninPosition".', __CLASS__, $v));
            }
            $payload['position'] = $v;
        }
        if (null !== $v = $this->prefix) {
            $payload['prefix'] = $v;
        }

        return $payload;
    }
}
