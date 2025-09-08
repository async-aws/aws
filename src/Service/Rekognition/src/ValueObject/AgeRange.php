<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * Structure containing the estimated age range, in years, for a face.
 *
 * Amazon Rekognition estimates an age range for faces detected in the input image. Estimated age ranges can overlap. A
 * face of a 5-year-old might have an estimated range of 4-6, while the face of a 6-year-old might have an estimated
 * range of 4-8.
 */
final class AgeRange
{
    /**
     * The lowest estimated age.
     *
     * @var int|null
     */
    private $low;

    /**
     * The highest estimated age.
     *
     * @var int|null
     */
    private $high;

    /**
     * @param array{
     *   Low?: int|null,
     *   High?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->low = $input['Low'] ?? null;
        $this->high = $input['High'] ?? null;
    }

    /**
     * @param array{
     *   Low?: int|null,
     *   High?: int|null,
     * }|AgeRange $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHigh(): ?int
    {
        return $this->high;
    }

    public function getLow(): ?int
    {
        return $this->low;
    }
}
