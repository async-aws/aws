<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class MetricDataQuery
{
    /**
     * A short name used to tie this object to the results in the response. This name must be unique within a single call to
     * `GetMetricData`. If you are performing math expressions on this set of data, this name represents that data and can
     * serve as a variable in the mathematical expression. The valid characters are letters, numbers, and underscore. The
     * first character must be a lowercase letter.
     */
    private $Id;

    /**
     * The metric to be returned, along with statistics, period, and units. Use this parameter only if this object is
     * retrieving a metric and not performing a math expression on returned data.
     */
    private $MetricStat;

    /**
     * The math expression to be performed on the returned data, if this object is performing a math expression. This
     * expression can use the `Id` of the other metrics to refer to those metrics, and can also use the `Id` of other
     * expressions to use the result of those expressions. For more information about metric math expressions, see Metric
     * Math Syntax and Functions in the *Amazon CloudWatch User Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/using-metric-math.html#metric-math-syntax
     */
    private $Expression;

    /**
     * A human-readable label for this metric or expression. This is especially useful if this is an expression, so that you
     * know what the value represents. If the metric or expression is shown in a CloudWatch dashboard widget, the label is
     * shown. If Label is omitted, CloudWatch generates a default.
     */
    private $Label;

    /**
     * When used in `GetMetricData`, this option indicates whether to return the timestamps and raw data values of this
     * metric. If you are performing this call just to do math expressions and do not also need the raw data returned, you
     * can specify `False`. If you omit this, the default of `True` is used.
     */
    private $ReturnData;

    /**
     * The granularity, in seconds, of the returned data points. For metrics with regular resolution, a period can be as
     * short as one minute (60 seconds) and must be a multiple of 60. For high-resolution metrics that are collected at
     * intervals of less than one minute, the period can be 1, 5, 10, 30, 60, or any multiple of 60. High-resolution metrics
     * are those metrics stored by a `PutMetricData` operation that includes a `StorageResolution of 1 second`.
     */
    private $Period;

    /**
     * @param array{
     *   Id: string,
     *   MetricStat?: null|\AsyncAws\CloudWatch\ValueObject\MetricStat|array,
     *   Expression?: null|string,
     *   Label?: null|string,
     *   ReturnData?: null|bool,
     *   Period?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Id = $input['Id'] ?? null;
        $this->MetricStat = isset($input['MetricStat']) ? MetricStat::create($input['MetricStat']) : null;
        $this->Expression = $input['Expression'] ?? null;
        $this->Label = $input['Label'] ?? null;
        $this->ReturnData = $input['ReturnData'] ?? null;
        $this->Period = $input['Period'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExpression(): ?string
    {
        return $this->Expression;
    }

    public function getId(): string
    {
        return $this->Id;
    }

    public function getLabel(): ?string
    {
        return $this->Label;
    }

    public function getMetricStat(): ?MetricStat
    {
        return $this->MetricStat;
    }

    public function getPeriod(): ?int
    {
        return $this->Period;
    }

    public function getReturnData(): ?bool
    {
        return $this->ReturnData;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Id) {
            throw new InvalidArgument(sprintf('Missing parameter "Id" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Id'] = $v;
        if (null !== $v = $this->MetricStat) {
            foreach ($v->requestBody() as $bodyKey => $bodyValue) {
                $payload["MetricStat.$bodyKey"] = $bodyValue;
            }
        }
        if (null !== $v = $this->Expression) {
            $payload['Expression'] = $v;
        }
        if (null !== $v = $this->Label) {
            $payload['Label'] = $v;
        }
        if (null !== $v = $this->ReturnData) {
            $payload['ReturnData'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->Period) {
            $payload['Period'] = $v;
        }

        return $payload;
    }
}
