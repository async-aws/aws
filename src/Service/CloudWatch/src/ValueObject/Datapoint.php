<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\CloudWatch\Enum\StandardUnit;

/**
 * Encapsulates the statistical data that CloudWatch computes from metric data.
 */
final class Datapoint
{
    /**
     * The time stamp used for the data point.
     *
     * @var \DateTimeImmutable|null
     */
    private $timestamp;

    /**
     * The number of metric values that contributed to the aggregate value of this data point.
     *
     * @var float|null
     */
    private $sampleCount;

    /**
     * The average of the metric values that correspond to the data point.
     *
     * @var float|null
     */
    private $average;

    /**
     * The sum of the metric values for the data point.
     *
     * @var float|null
     */
    private $sum;

    /**
     * The minimum metric value for the data point.
     *
     * @var float|null
     */
    private $minimum;

    /**
     * The maximum metric value for the data point.
     *
     * @var float|null
     */
    private $maximum;

    /**
     * The standard unit for the data point.
     *
     * @var StandardUnit::*|null
     */
    private $unit;

    /**
     * The percentile statistic for the data point.
     *
     * @var array<string, float>|null
     */
    private $extendedStatistics;

    /**
     * @param array{
     *   Timestamp?: \DateTimeImmutable|null,
     *   SampleCount?: float|null,
     *   Average?: float|null,
     *   Sum?: float|null,
     *   Minimum?: float|null,
     *   Maximum?: float|null,
     *   Unit?: StandardUnit::*|null,
     *   ExtendedStatistics?: array<string, float>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->timestamp = $input['Timestamp'] ?? null;
        $this->sampleCount = $input['SampleCount'] ?? null;
        $this->average = $input['Average'] ?? null;
        $this->sum = $input['Sum'] ?? null;
        $this->minimum = $input['Minimum'] ?? null;
        $this->maximum = $input['Maximum'] ?? null;
        $this->unit = $input['Unit'] ?? null;
        $this->extendedStatistics = $input['ExtendedStatistics'] ?? null;
    }

    /**
     * @param array{
     *   Timestamp?: \DateTimeImmutable|null,
     *   SampleCount?: float|null,
     *   Average?: float|null,
     *   Sum?: float|null,
     *   Minimum?: float|null,
     *   Maximum?: float|null,
     *   Unit?: StandardUnit::*|null,
     *   ExtendedStatistics?: array<string, float>|null,
     * }|Datapoint $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAverage(): ?float
    {
        return $this->average;
    }

    /**
     * @return array<string, float>
     */
    public function getExtendedStatistics(): array
    {
        return $this->extendedStatistics ?? [];
    }

    public function getMaximum(): ?float
    {
        return $this->maximum;
    }

    public function getMinimum(): ?float
    {
        return $this->minimum;
    }

    public function getSampleCount(): ?float
    {
        return $this->sampleCount;
    }

    public function getSum(): ?float
    {
        return $this->sum;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @return StandardUnit::*|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }
}
