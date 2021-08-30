<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The statistical values for the metric.
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
        $this->sampleCount = $input['SampleCount'] ?? null;
        $this->sum = $input['Sum'] ?? null;
        $this->minimum = $input['Minimum'] ?? null;
        $this->maximum = $input['Maximum'] ?? null;
    }

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
        if (null === $v = $this->sampleCount) {
            throw new InvalidArgument(sprintf('Missing parameter "SampleCount" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SampleCount'] = $v;
        if (null === $v = $this->sum) {
            throw new InvalidArgument(sprintf('Missing parameter "Sum" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Sum'] = $v;
        if (null === $v = $this->minimum) {
            throw new InvalidArgument(sprintf('Missing parameter "Minimum" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Minimum'] = $v;
        if (null === $v = $this->maximum) {
            throw new InvalidArgument(sprintf('Missing parameter "Maximum" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Maximum'] = $v;

        return $payload;
    }
}
