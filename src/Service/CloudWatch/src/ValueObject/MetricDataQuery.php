<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * This structure is used in both `GetMetricData` and `PutMetricAlarm`. The supported use of this structure is different
 * for those two operations.
 *
 * When used in `GetMetricData`, it indicates the metric data to return, and whether this call is just retrieving a
 * batch set of data for one metric, or is performing a Metrics Insights query or a math expression. A single
 * `GetMetricData` call can include up to 500 `MetricDataQuery` structures.
 *
 * When used in `PutMetricAlarm`, it enables you to create an alarm based on a metric math expression. Each
 * `MetricDataQuery` in the array specifies either a metric to retrieve, or a math expression to be performed on
 * retrieved metrics. A single `PutMetricAlarm` call can include up to 20 `MetricDataQuery` structures in the array. The
 * 20 structures can include as many as 10 structures that contain a `MetricStat` parameter to retrieve a metric, and as
 * many as 10 structures that contain the `Expression` parameter to perform a math expression. Of those `Expression`
 * structures, one must have `true` as the value for `ReturnData`. The result of this expression is the value the alarm
 * watches.
 *
 * Any expression used in a `PutMetricAlarm` operation must return a single time series. For more information, see
 * Metric Math Syntax and Functions [^1] in the *Amazon CloudWatch User Guide*.
 *
 * Some of the parameters of this structure also have different uses whether you are using this structure in a
 * `GetMetricData` operation or a `PutMetricAlarm` operation. These differences are explained in the following parameter
 * list.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/using-metric-math.html#metric-math-syntax
 */
final class MetricDataQuery
{
    /**
     * A short name used to tie this object to the results in the response. This name must be unique within a single call to
     * `GetMetricData`. If you are performing math expressions on this set of data, this name represents that data and can
     * serve as a variable in the mathematical expression. The valid characters are letters, numbers, and underscore. The
     * first character must be a lowercase letter.
     *
     * @var string
     */
    private $id;

    /**
     * The metric to be returned, along with statistics, period, and units. Use this parameter only if this object is
     * retrieving a metric and not performing a math expression on returned data.
     *
     * Within one MetricDataQuery object, you must specify either `Expression` or `MetricStat` but not both.
     *
     * @var MetricStat|null
     */
    private $metricStat;

    /**
     * This field can contain either a Metrics Insights query, or a metric math expression to be performed on the returned
     * data. For more information about Metrics Insights queries, see Metrics Insights query components and syntax [^1] in
     * the *Amazon CloudWatch User Guide*.
     *
     * A math expression can use the `Id` of the other metrics or queries to refer to those metrics, and can also use the
     * `Id` of other expressions to use the result of those expressions. For more information about metric math expressions,
     * see Metric Math Syntax and Functions [^2] in the *Amazon CloudWatch User Guide*.
     *
     * Within each MetricDataQuery object, you must specify either `Expression` or `MetricStat` but not both.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/cloudwatch-metrics-insights-querylanguage
     * [^2]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/using-metric-math.html#metric-math-syntax
     *
     * @var string|null
     */
    private $expression;

    /**
     * A human-readable label for this metric or expression. This is especially useful if this is an expression, so that you
     * know what the value represents. If the metric or expression is shown in a CloudWatch dashboard widget, the label is
     * shown. If Label is omitted, CloudWatch generates a default.
     *
     * You can put dynamic expressions into a label, so that it is more descriptive. For more information, see Using Dynamic
     * Labels [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/graph-dynamic-labels.html
     *
     * @var string|null
     */
    private $label;

    /**
     * When used in `GetMetricData`, this option indicates whether to return the timestamps and raw data values of this
     * metric. If you are performing this call just to do math expressions and do not also need the raw data returned, you
     * can specify `false`. If you omit this, the default of `true` is used.
     *
     * When used in `PutMetricAlarm`, specify `true` for the one expression result to use as the alarm. For all other
     * metrics and expressions in the same `PutMetricAlarm` operation, specify `ReturnData` as False.
     *
     * @var bool|null
     */
    private $returnData;

    /**
     * The granularity, in seconds, of the returned data points. For metrics with regular resolution, a period can be as
     * short as one minute (60 seconds) and must be a multiple of 60. For high-resolution metrics that are collected at
     * intervals of less than one minute, the period can be 1, 5, 10, 20, 30, 60, or any multiple of 60. High-resolution
     * metrics are those metrics stored by a `PutMetricData` operation that includes a `StorageResolution of 1 second`.
     *
     * @var int|null
     */
    private $period;

    /**
     * The ID of the account where the metrics are located.
     *
     * If you are performing a `GetMetricData` operation in a monitoring account, use this to specify which account to
     * retrieve this metric from.
     *
     * If you are performing a `PutMetricAlarm` operation, use this to specify which account contains the metric that the
     * alarm is watching.
     *
     * @var string|null
     */
    private $accountId;

    /**
     * @param array{
     *   Id: string,
     *   MetricStat?: MetricStat|array|null,
     *   Expression?: string|null,
     *   Label?: string|null,
     *   ReturnData?: bool|null,
     *   Period?: int|null,
     *   AccountId?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->metricStat = isset($input['MetricStat']) ? MetricStat::create($input['MetricStat']) : null;
        $this->expression = $input['Expression'] ?? null;
        $this->label = $input['Label'] ?? null;
        $this->returnData = $input['ReturnData'] ?? null;
        $this->period = $input['Period'] ?? null;
        $this->accountId = $input['AccountId'] ?? null;
    }

    /**
     * @param array{
     *   Id: string,
     *   MetricStat?: MetricStat|array|null,
     *   Expression?: string|null,
     *   Label?: string|null,
     *   ReturnData?: bool|null,
     *   Period?: int|null,
     *   AccountId?: string|null,
     * }|MetricDataQuery $input
     */
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
        $v = $this->id;
        $payload['Id'] = $v;
        if (null !== $v = $this->metricStat) {
            $payload['MetricStat'] = $v->requestBody();
        }
        if (null !== $v = $this->expression) {
            $payload['Expression'] = $v;
        }
        if (null !== $v = $this->label) {
            $payload['Label'] = $v;
        }
        if (null !== $v = $this->returnData) {
            $payload['ReturnData'] = (bool) $v;
        }
        if (null !== $v = $this->period) {
            $payload['Period'] = $v;
        }
        if (null !== $v = $this->accountId) {
            $payload['AccountId'] = $v;
        }

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
