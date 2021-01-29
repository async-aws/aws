<?php

namespace AsyncAws\Rekognition\ValueObject;

/**
 * The estimated age range, in years, for the face. Low represents the lowest estimated age and High represents the
 * highest estimated age.
 */
final class AgeRange
{
    /**
     * The lowest estimated age.
     */
    private $low;

    /**
     * The highest estimated age.
     */
    private $high;

    /**
     * @param array{
     *   Low?: null|int,
     *   High?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->low = $input['Low'] ?? null;
        $this->high = $input['High'] ?? null;
    }

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
