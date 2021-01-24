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
    private $Low;

    /**
     * The highest estimated age.
     */
    private $High;

    /**
     * @param array{
     *   Low?: null|int,
     *   High?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Low = $input['Low'] ?? null;
        $this->High = $input['High'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHigh(): ?int
    {
        return $this->High;
    }

    public function getLow(): ?int
    {
        return $this->Low;
    }
}
