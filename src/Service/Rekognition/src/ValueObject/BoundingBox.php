<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Bounding box of the face. Default attribute.
 */
final class BoundingBox
{
    /**
     * Width of the bounding box as a ratio of the overall image width.
     */
    private $width;

    /**
     * Height of the bounding box as a ratio of the overall image height.
     */
    private $height;

    /**
     * Left coordinate of the bounding box as a ratio of overall image width.
     */
    private $left;

    /**
     * Top coordinate of the bounding box as a ratio of overall image height.
     */
    private $top;

    /**
     * @param array{
     *   Width?: null|float,
     *   Height?: null|float,
     *   Left?: null|float,
     *   Top?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->width = $input['Width'] ?? null;
        $this->height = $input['Height'] ?? null;
        $this->left = $input['Left'] ?? null;
        $this->top = $input['Top'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function getLeft(): ?float
    {
        return $this->left;
    }

    public function getTop(): ?float
    {
        return $this->top;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }
}
