<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Identifies the bounding box around the label, face, text, object of interest, or personal protective equipment. The
 * `left` (x-coordinate) and `top` (y-coordinate) are coordinates representing the top and left sides of the bounding
 * box. Note that the upper-left corner of the image is the origin (0,0).
 *
 * The `top` and `left` values returned are ratios of the overall image size. For example, if the input image is 700x200
 * pixels, and the top-left coordinate of the bounding box is 350x50 pixels, the API returns a `left` value of 0.5
 * (350/700) and a `top` value of 0.25 (50/200).
 *
 * The `width` and `height` values represent the dimensions of the bounding box as a ratio of the overall image
 * dimension. For example, if the input image is 700x200 pixels, and the bounding box width is 70 pixels, the width
 * returned is 0.1.
 *
 * > The bounding box coordinates can have negative values. For example, if Amazon Rekognition is able to detect a face
 * > that is at the image edge and is only partially visible, the service can return coordinates that are outside the
 * > image bounds and, depending on the image edge, you might get negative values or values greater than 1 for the
 * > `left` or `top` values.
 */
final class BoundingBox
{
    /**
     * Width of the bounding box as a ratio of the overall image width.
     *
     * @var float|null
     */
    private $width;

    /**
     * Height of the bounding box as a ratio of the overall image height.
     *
     * @var float|null
     */
    private $height;

    /**
     * Left coordinate of the bounding box as a ratio of overall image width.
     *
     * @var float|null
     */
    private $left;

    /**
     * Top coordinate of the bounding box as a ratio of overall image height.
     *
     * @var float|null
     */
    private $top;

    /**
     * @param array{
     *   Width?: float|null,
     *   Height?: float|null,
     *   Left?: float|null,
     *   Top?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->width = $input['Width'] ?? null;
        $this->height = $input['Height'] ?? null;
        $this->left = $input['Left'] ?? null;
        $this->top = $input['Top'] ?? null;
    }

    /**
     * @param array{
     *   Width?: float|null,
     *   Height?: float|null,
     *   Left?: float|null,
     *   Top?: float|null,
     * }|BoundingBox $input
     */
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
