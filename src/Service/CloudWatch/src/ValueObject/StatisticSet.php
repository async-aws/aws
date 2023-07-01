<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a set of statistics that describes a specific metric.
 */
final class StatisticSet
{
    /**
     * The number of samples used for the statistic set.
     */
    private $sampleCount;

    /**
     * The sum of values for the sample set.
     */
    private $sum;

    /**
     * The minimum value of the sample set.
     */
    private $minimum;

    /**
     * The maximum value of the sample set.
     */
    private $maximum;

    /**
     * @param array{
     *   SampleCount: float,
     *   Sum: float,
     *   Minimum: float,
     *   Maximum: float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sampleCount = $input['SampleCount'] ?? $this->throwException(new InvalidArgument('Missing required field "SampleCount".'));
        $this->sum = $input['Sum'] ?? $this->throwException(new InvalidArgument('Missing required field "Sum".'));
        $this->minimum = $input['Minimum'] ?? $this->throwException(new InvalidArgument('Missing required field "Minimum".'));
        $this->maximum = $input['Maximum'] ?? $this->throwException(new InvalidArgument('Missing required field "Maximum".'));
    }

    /**
     * @param array{
     *   SampleCount: float,
     *   Sum: float,
     *   Minimum: float,
     *   Maximum: float,
     * }|StatisticSet $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaximum(): float
    {
        return $this->maximum;
    }

    public function getMinimum(): float
    {
        return $this->minimum;
    }

    public function getSampleCount(): float
    {
        return $this->sampleCount;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->sampleCount;
        $payload['SampleCount'] = $v;
        $v = $this->sum;
        $payload['Sum'] = $v;
        $v = $this->minimum;
        $payload['Minimum'] = $v;
        $v = $this->maximum;
        $payload['Maximum'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
