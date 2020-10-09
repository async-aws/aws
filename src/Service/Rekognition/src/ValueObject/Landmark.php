<?php

namespace AsyncAws\Rekognition\ValueObject;

use AsyncAws\Rekognition\Enum\LandmarkType;

final class Landmark
{
    /**
     * Type of landmark.
     */
    private $Type;

    /**
     * The x-coordinate of the landmark expressed as a ratio of the width of the image. The x-coordinate is measured from
     * the left-side of the image. For example, if the image is 700 pixels wide and the x-coordinate of the landmark is at
     * 350 pixels, this value is 0.5.
     */
    private $X;

    /**
     * The y-coordinate of the landmark expressed as a ratio of the height of the image. The y-coordinate is measured from
     * the top of the image. For example, if the image height is 200 pixels and the y-coordinate of the landmark is at 50
     * pixels, this value is 0.25.
     */
    private $Y;

    /**
     * @param array{
     *   Type?: null|LandmarkType::*,
     *   X?: null|float,
     *   Y?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Type = $input['Type'] ?? null;
        $this->X = $input['X'] ?? null;
        $this->Y = $input['Y'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return LandmarkType::*|null
     */
    public function getType(): ?string
    {
        return $this->Type;
    }

    public function getX(): ?float
    {
        return $this->X;
    }

    public function getY(): ?float
    {
        return $this->Y;
    }
}
