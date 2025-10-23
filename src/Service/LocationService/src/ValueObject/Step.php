<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents an element of a leg within a route. A step contains instructions for how to move to the next step in the
 * leg.
 */
final class Step
{
    /**
     * The starting position of a step. If the position is the first step in the leg, this position is the same as the start
     * position of the leg.
     *
     * @var float[]
     */
    private $startPosition;

    /**
     * The end position of a step. If the position the last step in the leg, this position is the same as the end position
     * of the leg.
     *
     * @var float[]
     */
    private $endPosition;

    /**
     * The travel distance between the step's `StartPosition` and `EndPosition`.
     *
     * @var float
     */
    private $distance;

    /**
     * The estimated travel time, in seconds, from the step's `StartPosition` to the `EndPosition`. . The travel mode and
     * departure time that you specify in the request determines the calculated time.
     *
     * @var float
     */
    private $durationSeconds;

    /**
     * Represents the start position, or index, in a sequence of steps within the leg's line string geometry. For example,
     * the index of the first step in a leg geometry is `0`.
     *
     * Included in the response for queries that set `IncludeLegGeometry` to `True`.
     *
     * @var int|null
     */
    private $geometryOffset;

    /**
     * @param array{
     *   StartPosition: float[],
     *   EndPosition: float[],
     *   Distance: float,
     *   DurationSeconds: float,
     *   GeometryOffset?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->startPosition = $input['StartPosition'] ?? $this->throwException(new InvalidArgument('Missing required field "StartPosition".'));
        $this->endPosition = $input['EndPosition'] ?? $this->throwException(new InvalidArgument('Missing required field "EndPosition".'));
        $this->distance = $input['Distance'] ?? $this->throwException(new InvalidArgument('Missing required field "Distance".'));
        $this->durationSeconds = $input['DurationSeconds'] ?? $this->throwException(new InvalidArgument('Missing required field "DurationSeconds".'));
        $this->geometryOffset = $input['GeometryOffset'] ?? null;
    }

    /**
     * @param array{
     *   StartPosition: float[],
     *   EndPosition: float[],
     *   Distance: float,
     *   DurationSeconds: float,
     *   GeometryOffset?: int|null,
     * }|Step $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function getDurationSeconds(): float
    {
        return $this->durationSeconds;
    }

    /**
     * @return float[]
     */
    public function getEndPosition(): array
    {
        return $this->endPosition;
    }

    public function getGeometryOffset(): ?int
    {
        return $this->geometryOffset;
    }

    /**
     * @return float[]
     */
    public function getStartPosition(): array
    {
        return $this->startPosition;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
