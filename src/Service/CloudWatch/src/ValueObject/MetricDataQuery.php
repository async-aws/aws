<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * This structure is used in both `GetMetricData` and `PutMetricAlarm`. The supported use of this structure is different
 * for those two operations.
 * When used in `GetMetricData`, it indicates the metric data to return, and whether this call is just retrieving a
 * batch set of data for one metric, or is performing a math expression on metric data. A single `GetMetricData` call
 * can include up to 500 `MetricDataQuery` structures.
 * When used in `PutMetricAlarm`, it enables you to create an alarm based on a metric math expression. Each
 * `MetricDataQuery` in the array specifies either a metric to retrieve, or a math expression to be performed on
 * retrieved metrics. A single `PutMetricAlarm` call can include up to 20 `MetricDataQuery` structures in the array. The
 * 20 structures can include as many as 10 structures that contain a `MetricStat` parameter to retrieve a metric, and as
 * many as 10 structures that contain the `Expression` parameter to perform a math expression. Of those `Expression`
 * structures, one must have `True` as the value for `ReturnData`. The result of this expression is the value the alarm
 * watches.
 * Any expression used in a `PutMetricAlarm` operation must return a single time series. For more information, see
 * Metric Math Syntax and Functions in the *Amazon CloudWatch User Guide*.
 * Some of the parameters of this structure also have different uses whether you are using this structure in a
 * `GetMetricData` operation or a `PutMetricAlarm` operation. These differences are explained in the following parameter
 * list.
 *
 * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/using-metric-math.html#metric-math-syntax
 */
final class MetricDataQuery
{
    /**
     * A short name used to tie this object to the results in the response. This name must be unique within a single call to
     * `GetMetricData`. If you are performing math expressions on this set of data, this name represents that data and can
     * serve as a variable in the mathematical expression. The valid characters are letters, numbers, and underscore. The
     * first character must be a lowercase letter.
     */
    private $id;

    /**
     * The metric to be returned, along with statistics, period, and units. Use this parameter only if this object is
     * retrieving a metric and not performing a math expression on returned data.
     */
    private $metricStat;

    /**
     * The math expression to be performed on the returned data, if this object is performing a math expression. This
     * expression can use the `Id` of the other metrics to refer to those metrics, and can also use the `Id` of other
     * expressions to use the result of those expressions. For more information about metric math expressions, see Metric
     * Math Syntax and Functions in the *Amazon CloudWatch User Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/using-metric-math.html#metric-math-syntax
     */
    private $expression;

    /**
     * A human-readable label for this metric or expression. This is especially useful if this is an expression, so that you
     * know what the value represents. If the metric or expression is shown in a CloudWatch dashboard widget, the label is
     * shown. If Label is omitted, CloudWatch generates a default.
     */
    private $label;

    /**
     * When used in `GetMetricData`, this option indicates whether to return the timestamps and raw data values of this
     * metric. If you are performing this call just to do math expressions and do not also need the raw data returned, you
     * can specify `False`. If you omit this, the default of `True` is used.
     */
    private $returnData;

    /**
     * The granularity, in seconds, of the returned data points. For metrics with regular resolution, a period can be as
     * short as one minute (60 seconds) and must be a multiple of 60. For high-resolution metrics that are collected at
     * intervals of less than one minute, the period can be 1, 5, 10, 30, 60, or any multiple of 60. High-resolution metrics
     * are those metrics stored by a `PutMetricData` operation that includes a `StorageResolution of 1 second`.
     */
    private $period;

    /**
     * The ID of the account where the metrics are located, if this is a cross-account alarm.
     */
    private $accountId;

    /**
     * @param array{
     *   Id: string,
     *   MetricStat?: null|MetricStat|array,
     *   Expression?: null|string,
     *   Label?: null|string,
     *   ReturnData?: null|bool,
     *   Period?: null|int,
     *   AccountId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->metricStat = isset($input['MetricStat']) ? MetricStat::create($input['MetricStat']) : null;
        $this->expression = $input['Expression'] ?? null;
        $this->label = $input['Label'] ?? null;
        $this->returnData = $input['ReturnData'] ?? null;
        $this->period = $input['Period'] ?? null;
        $this->accountId = $input['AccountId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccountId(): ?string
    {
        return $this->accountId;
    }

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getMetricStat(): ?MetricStat
    {
        return $this->metricStat;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function getReturnData(): ?bool
    {
        return $this->returnData;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->id) {
            throw new InvalidArgument(sprintf('Missing parameter "Id" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Id'] = $v;
        if (null !== $v = $this->metricStat) {
            foreach ($v->requestBody() as $bodyKey => $bodyValue) {
                $payload["MetricStat.$bodyKey"] = $bodyValue;
            }
        }
        if (null !== $v = $this->expression) {
            $payload['Expression'] = $v;
        }
        if (null !== $v = $this->label) {
            $payload['Label'] = $v;
        }
        if (null !== $v = $this->returnData) {
            $payload['ReturnData'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->period) {
            $payload['Period'] = $v;
        }
        if (null !== $v = $this->accountId) {
            $payload['AccountId'] = $v;
        }

        return $payload;
    }
}
