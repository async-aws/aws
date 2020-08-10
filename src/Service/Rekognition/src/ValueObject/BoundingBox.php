<?php

namespace AsyncAws\Rekognition\ValueObject;

final class BoundingBox
{
    /**
     * Width of the bounding box as a ratio of the overall image width.
     */
    private $Width;

    /**
     * Height of the bounding box as a ratio of the overall image height.
     */
    private $Height;

    /**
     * Left coordinate of the bounding box as a ratio of overall image width.
     */
    private $Left;

    /**
     * Top coordinate of the bounding box as a ratio of overall image height.
     */
    private $Top;

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
        $this->Width = $input['Width'] ?? null;
        $this->Height = $input['Height'] ?? null;
        $this->Left = $input['Left'] ?? null;
        $this->Top = $input['Top'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHeight(): ?float
    {
        return $this->Height;
    }

    public function getLeft(): ?float
    {
        return $this->Left;
    }

    public function getTop(): ?float
    {
        return $this->Top;
    }

    public function getWidth(): ?float
    {
        return $this->Width;
    }
}
