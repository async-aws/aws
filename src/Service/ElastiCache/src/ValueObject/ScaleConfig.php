<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Configuration settings for horizontal or vertical scaling operations on Memcached clusters.
 */
final class ScaleConfig
{
    /**
     * The percentage by which to scale the Memcached cluster, either horizontally by adding nodes or vertically by
     * increasing resources.
     *
     * @var int|null
     */
    private $scalePercentage;

    /**
     * The time interval in seconds between scaling operations when performing gradual scaling for a Memcached cluster.
     *
     * @var int|null
     */
    private $scaleIntervalMinutes;

    /**
     * @param array{
     *   ScalePercentage?: int|null,
     *   ScaleIntervalMinutes?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->scalePercentage = $input['ScalePercentage'] ?? null;
        $this->scaleIntervalMinutes = $input['ScaleIntervalMinutes'] ?? null;
    }

    /**
     * @param array{
     *   ScalePercentage?: int|null,
     *   ScaleIntervalMinutes?: int|null,
     * }|ScaleConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getScaleIntervalMinutes(): ?int
    {
        return $this->scaleIntervalMinutes;
    }

    public function getScalePercentage(): ?int
    {
        return $this->scalePercentage;
    }
}
