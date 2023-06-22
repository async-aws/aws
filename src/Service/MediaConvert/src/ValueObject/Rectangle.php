<?php

namespace AsyncAws\MediaConvert\ValueObject;

/**
 * Use Rectangle to identify a specific area of the video frame.
 */
final class Rectangle
{
    /**
     * Height of rectangle in pixels. Specify only even numbers.
     */
    private $height;

    /**
     * Width of rectangle in pixels. Specify only even numbers.
     */
    private $width;

    /**
     * The distance, in pixels, between the rectangle and the left edge of the video frame. Specify only even numbers.
     */
    private $x;

    /**
     * The distance, in pixels, between the rectangle and the top edge of the video frame. Specify only even numbers.
     */
    private $y;

    /**
     * @param array{
     *   Height?: null|int,
     *   Width?: null|int,
     *   X?: null|int,
     *   Y?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->height = $input['Height'] ?? null;
        $this->width = $input['Width'] ?? null;
        $this->x = $input['X'] ?? null;
        $this->y = $input['Y'] ?? null;
    }

    /**
     * @param array{
     *   Height?: null|int,
     *   Width?: null|int,
     *   X?: null|int,
     *   Y?: null|int,
     * }|Rectangle $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?int
    {
        return $this->height;
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
