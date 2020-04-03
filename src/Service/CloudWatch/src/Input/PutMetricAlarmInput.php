<?php

namespace AsyncAws\CloudWatch\Input;

use AsyncAws\CloudWatch\Enum\ComparisonOperator;
use AsyncAws\CloudWatch\Enum\StandardUnit;
use AsyncAws\CloudWatch\Enum\Statistic;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\CloudWatch\ValueObject\Metric;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use AsyncAws\CloudWatch\ValueObject\Tag;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

class PutMetricAlarmInput implements Input
{
    /**
     * The name for the alarm. This name must be unique within your AWS account.
     *
     * @required
     *
     * @var string|null
     */
    private $AlarmName;

    /**
     * The description for the alarm.
     *
     * @var string|null
     */
    private $AlarmDescription;

    /**
     * Indicates whether actions should be executed during any changes to the alarm state. The default is `TRUE`.
     *
     * @var bool|null
     */
    private $ActionsEnabled;

    /**
     * The actions to execute when this alarm transitions to an `OK` state from any other state. Each action is specified as
     * an Amazon Resource Name (ARN).
     *
     * @var string[]
     */
    private $OKActions;

    /**
     * The actions to execute when this alarm transitions to the `ALARM` state from any other state. Each action is
     * specified as an Amazon Resource Name (ARN).
     *
     * @var string[]
     */
    private $AlarmActions;

    /**
     * The actions to execute when this alarm transitions to the `INSUFFICIENT_DATA` state from any other state. Each action
     * is specified as an Amazon Resource Name (ARN).
     *
     * @var string[]
     */
    private $InsufficientDataActions;

    /**
     * The name for the metric associated with the alarm. For each `PutMetricAlarm` operation, you must specify either
     * `MetricName` or a `Metrics` array.
     *
     * @var string|null
     */
    private $MetricName;

    /**
     * The namespace for the metric associated specified in `MetricName`.
     *
     * @var string|null
     */
    private $Namespace;

    /**
     * The statistic for the metric specified in `MetricName`, other than percentile. For percentile statistics, use
     * `ExtendedStatistic`. When you call `PutMetricAlarm` and specify a `MetricName`, you must specify either `Statistic`
     * or `ExtendedStatistic,` but not both.
     *
     * @var null|Statistic::*
     */
    private $Statistic;

    /**
     * The percentile statistic for the metric specified in `MetricName`. Specify a value between p0.0 and p100. When you
     * call `PutMetricAlarm` and specify a `MetricName`, you must specify either `Statistic` or `ExtendedStatistic,` but not
     * both.
     *
     * @var string|null
     */
    private $ExtendedStatistic;

    /**
     * The dimensions for the metric specified in `MetricName`.
     *
     * @var Dimension[]
     */
    private $Dimensions;

    /**
     * The length, in seconds, used each time the metric specified in `MetricName` is evaluated. Valid values are 10, 30,
     * and any multiple of 60.
     *
     * @var int|null
     */
    private $Period;

    /**
     * The unit of measure for the statistic. For example, the units for the Amazon EC2 NetworkIn metric are Bytes because
     * NetworkIn tracks the number of bytes that an instance receives on all network interfaces. You can also specify a unit
     * when you create a custom metric. Units help provide conceptual meaning to your data. Metric data points that specify
     * a unit of measure, such as Percent, are aggregated separately.
     *
     * @var null|StandardUnit::*
     */
    private $Unit;

    /**
     * The number of periods over which data is compared to the specified threshold. If you are setting an alarm that
     * requires that a number of consecutive data points be breaching to trigger the alarm, this value specifies that
     * number. If you are setting an "M out of N" alarm, this value is the N.
     *
     * @required
     *
     * @var int|null
     */
    private $EvaluationPeriods;

    /**
     * The number of data points that must be breaching to trigger the alarm. This is used only if you are setting an "M out
     * of N" alarm. In that case, this value is the M. For more information, see Evaluating an Alarm in the *Amazon
     * CloudWatch User Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/AlarmThatSendsEmail.html#alarm-evaluation
     *
     * @var int|null
     */
    private $DatapointsToAlarm;

    /**
     * The value against which the specified statistic is compared.
     *
     * @var float|null
     */
    private $Threshold;

    /**
     * The arithmetic operation to use when comparing the specified statistic and threshold. The specified statistic value
     * is used as the first operand.
     *
     * @required
     *
     * @var null|ComparisonOperator::*
     */
    private $ComparisonOperator;

    /**
     * Sets how this alarm is to handle missing data points. If `TreatMissingData` is omitted, the default behavior of
     * `missing` is used. For more information, see Configuring How CloudWatch Alarms Treats Missing Data.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/AlarmThatSendsEmail.html#alarms-and-missing-data
     *
     * @var string|null
     */
    private $TreatMissingData;

    /**
     * Used only for alarms based on percentiles. If you specify `ignore`, the alarm state does not change during periods
     * with too few data points to be statistically significant. If you specify `evaluate` or omit this parameter, the alarm
     * is always evaluated and possibly changes state no matter how many data points are available. For more information,
     * see Percentile-Based CloudWatch Alarms and Low Data Samples.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/AlarmThatSendsEmail.html#percentiles-with-low-samples
     *
     * @var string|null
     */
    private $EvaluateLowSampleCountPercentile;

    /**
     * An array of `MetricDataQuery` structures that enable you to create an alarm based on the result of a metric math
     * expression. For each `PutMetricAlarm` operation, you must specify either `MetricName` or a `Metrics` array.
     *
     * @var MetricDataQuery[]
     */
    private $Metrics;

    /**
     * A list of key-value pairs to associate with the alarm. You can associate as many as 50 tags with an alarm.
     *
     * @var Tag[]
     */
    private $Tags;

    /**
     * If this is an alarm based on an anomaly detection model, make this value match the ID of the `ANOMALY_DETECTION_BAND`
     * function.
     *
     * @var string|null
     */
    private $ThresholdMetricId;

    /**
     * @param array{
     *   AlarmName?: string,
     *   AlarmDescription?: string,
     *   ActionsEnabled?: bool,
     *   OKActions?: string[],
     *   AlarmActions?: string[],
     *   InsufficientDataActions?: string[],
     *   MetricName?: string,
     *   Namespace?: string,
     *   Statistic?: \AsyncAws\CloudWatch\Enum\Statistic::*,
     *   ExtendedStatistic?: string,
     *   Dimensions?: \AsyncAws\CloudWatch\ValueObject\Dimension[],
     *   Period?: int,
     *   Unit?: \AsyncAws\CloudWatch\Enum\StandardUnit::*,
     *   EvaluationPeriods?: int,
     *   DatapointsToAlarm?: int,
     *   Threshold?: float,
     *   ComparisonOperator?: \AsyncAws\CloudWatch\Enum\ComparisonOperator::*,
     *   TreatMissingData?: string,
     *   EvaluateLowSampleCountPercentile?: string,
     *   Metrics?: \AsyncAws\CloudWatch\ValueObject\MetricDataQuery[],
     *   Tags?: \AsyncAws\CloudWatch\ValueObject\Tag[],
     *   ThresholdMetricId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->AlarmName = $input['AlarmName'] ?? null;
        $this->AlarmDescription = $input['AlarmDescription'] ?? null;
        $this->ActionsEnabled = $input['ActionsEnabled'] ?? null;
        $this->OKActions = $input['OKActions'] ?? [];
        $this->AlarmActions = $input['AlarmActions'] ?? [];
        $this->InsufficientDataActions = $input['InsufficientDataActions'] ?? [];
        $this->MetricName = $input['MetricName'] ?? null;
        $this->Namespace = $input['Namespace'] ?? null;
        $this->Statistic = $input['Statistic'] ?? null;
        $this->ExtendedStatistic = $input['ExtendedStatistic'] ?? null;
        $this->Dimensions = array_map([Dimension::class, 'create'], $input['Dimensions'] ?? []);
        $this->Period = $input['Period'] ?? null;
        $this->Unit = $input['Unit'] ?? null;
        $this->EvaluationPeriods = $input['EvaluationPeriods'] ?? null;
        $this->DatapointsToAlarm = $input['DatapointsToAlarm'] ?? null;
        $this->Threshold = $input['Threshold'] ?? null;
        $this->ComparisonOperator = $input['ComparisonOperator'] ?? null;
        $this->TreatMissingData = $input['TreatMissingData'] ?? null;
        $this->EvaluateLowSampleCountPercentile = $input['EvaluateLowSampleCountPercentile'] ?? null;
        $this->Metrics = array_map([MetricDataQuery::class, 'create'], $input['Metrics'] ?? []);
        $this->Tags = array_map([Tag::class, 'create'], $input['Tags'] ?? []);
        $this->ThresholdMetricId = $input['ThresholdMetricId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getActionsEnabled(): ?bool
    {
        return $this->ActionsEnabled;
    }

    /**
     * @return string[]
     */
    public function getAlarmActions(): array
    {
        return $this->AlarmActions;
    }

    public function getAlarmDescription(): ?string
    {
        return $this->AlarmDescription;
    }

    public function getAlarmName(): ?string
    {
        return $this->AlarmName;
    }

    /**
     * @return ComparisonOperator::*|null
     */
    public function getComparisonOperator(): ?string
    {
        return $this->ComparisonOperator;
    }

    public function getDatapointsToAlarm(): ?int
    {
        return $this->DatapointsToAlarm;
    }

    /**
     * @return Dimension[]
     */
    public function getDimensions(): array
    {
        return $this->Dimensions;
    }

    public function getEvaluateLowSampleCountPercentile(): ?string
    {
        return $this->EvaluateLowSampleCountPercentile;
    }

    public function getEvaluationPeriods(): ?int
    {
        return $this->EvaluationPeriods;
    }

    public function getExtendedStatistic(): ?string
    {
        return $this->ExtendedStatistic;
    }

    /**
     * @return string[]
     */
    public function getInsufficientDataActions(): array
    {
        return $this->InsufficientDataActions;
    }

    public function getMetricName(): ?string
    {
        return $this->MetricName;
    }

    /**
     * @return MetricDataQuery[]
     */
    public function getMetrics(): array
    {
        return $this->Metrics;
    }

    public function getNamespace(): ?string
    {
        return $this->Namespace;
    }

    /**
     * @return string[]
     */
    public function getOKActions(): array
    {
        return $this->OKActions;
    }

    public function getPeriod(): ?int
    {
        return $this->Period;
    }

    /**
     * @return Statistic::*|null
     */
    public function getStatistic(): ?string
    {
        return $this->Statistic;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags;
    }

    public function getThreshold(): ?float
    {
        return $this->Threshold;
    }

    public function getThresholdMetricId(): ?string
    {
        return $this->ThresholdMetricId;
    }

    public function getTreatMissingData(): ?string
    {
        return $this->TreatMissingData;
    }

    /**
     * @return StandardUnit::*|null
     */
    public function getUnit(): ?string
    {
        return $this->Unit;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'PutMetricAlarm', 'Version' => '2010-08-01'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setActionsEnabled(?bool $value): self
    {
        $this->ActionsEnabled = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setAlarmActions(array $value): self
    {
        $this->AlarmActions = $value;

        return $this;
    }

    public function setAlarmDescription(?string $value): self
    {
        $this->AlarmDescription = $value;

        return $this;
    }

    public function setAlarmName(?string $value): self
    {
        $this->AlarmName = $value;

        return $this;
    }

    /**
     * @param ComparisonOperator::*|null $value
     */
    public function setComparisonOperator(?string $value): self
    {
        $this->ComparisonOperator = $value;

        return $this;
    }

    public function setDatapointsToAlarm(?int $value): self
    {
        $this->DatapointsToAlarm = $value;

        return $this;
    }

    /**
     * @param Dimension[] $value
     */
    public function setDimensions(array $value): self
    {
        $this->Dimensions = $value;

        return $this;
    }

    public function setEvaluateLowSampleCountPercentile(?string $value): self
    {
        $this->EvaluateLowSampleCountPercentile = $value;

        return $this;
    }

    public function setEvaluationPeriods(?int $value): self
    {
        $this->EvaluationPeriods = $value;

        return $this;
    }

    public function setExtendedStatistic(?string $value): self
    {
        $this->ExtendedStatistic = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setInsufficientDataActions(array $value): self
    {
        $this->InsufficientDataActions = $value;

        return $this;
    }

    public function setMetricName(?string $value): self
    {
        $this->MetricName = $value;

        return $this;
    }

    /**
     * @param MetricDataQuery[] $value
     */
    public function setMetrics(array $value): self
    {
        $this->Metrics = $value;

        return $this;
    }

    public function setNamespace(?string $value): self
    {
        $this->Namespace = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setOKActions(array $value): self
    {
        $this->OKActions = $value;

        return $this;
    }

    public function setPeriod(?int $value): self
    {
        $this->Period = $value;

        return $this;
    }

    /**
     * @param Statistic::*|null $value
     */
    public function setStatistic(?string $value): self
    {
        $this->Statistic = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->Tags = $value;

        return $this;
    }

    public function setThreshold(?float $value): self
    {
        $this->Threshold = $value;

        return $this;
    }

    public function setThresholdMetricId(?string $value): self
    {
        $this->ThresholdMetricId = $value;

        return $this;
    }

    public function setTreatMissingData(?string $value): self
    {
        $this->TreatMissingData = $value;

        return $this;
    }

    /**
     * @param StandardUnit::*|null $value
     */
    public function setUnit(?string $value): self
    {
        $this->Unit = $value;

        return $this;
    }

    /**
     * @internal
     */
    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->AlarmName) {
            throw new InvalidArgument(sprintf('Missing parameter "AlarmName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AlarmName'] = $v;
        if (null !== $v = $this->AlarmDescription) {
            $payload['AlarmDescription'] = $v;
        }
        if (null !== $v = $this->ActionsEnabled) {
            $payload['ActionsEnabled'] = $v ? 'true' : 'false';
        }

        $index = 0;
        foreach ($this->OKActions as $mapValue) {
            ++$index;
            $payload["OKActions.member.$index"] = $mapValue;
        }

        $index = 0;
        foreach ($this->AlarmActions as $mapValue) {
            ++$index;
            $payload["AlarmActions.member.$index"] = $mapValue;
        }

        $index = 0;
        foreach ($this->InsufficientDataActions as $mapValue) {
            ++$index;
            $payload["InsufficientDataActions.member.$index"] = $mapValue;
        }

        if (null !== $v = $this->MetricName) {
            $payload['MetricName'] = $v;
        }
        if (null !== $v = $this->Namespace) {
            $payload['Namespace'] = $v;
        }
        if (null !== $v = $this->Statistic) {
            if (!Statistic::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Statistic" for "%s". The value "%s" is not a valid "Statistic".', __CLASS__, $v));
            }
            $payload['Statistic'] = $v;
        }
        if (null !== $v = $this->ExtendedStatistic) {
            $payload['ExtendedStatistic'] = $v;
        }

        $index = 0;
        foreach ($this->Dimensions as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["Dimensions.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        if (null !== $v = $this->Period) {
            $payload['Period'] = $v;
        }
        if (null !== $v = $this->Unit) {
            if (!StandardUnit::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Unit" for "%s". The value "%s" is not a valid "StandardUnit".', __CLASS__, $v));
            }
            $payload['Unit'] = $v;
        }
        if (null === $v = $this->EvaluationPeriods) {
            throw new InvalidArgument(sprintf('Missing parameter "EvaluationPeriods" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['EvaluationPeriods'] = $v;
        if (null !== $v = $this->DatapointsToAlarm) {
            $payload['DatapointsToAlarm'] = $v;
        }
        if (null !== $v = $this->Threshold) {
            $payload['Threshold'] = $v;
        }
        if (null === $v = $this->ComparisonOperator) {
            throw new InvalidArgument(sprintf('Missing parameter "ComparisonOperator" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ComparisonOperator::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "ComparisonOperator" for "%s". The value "%s" is not a valid "ComparisonOperator".', __CLASS__, $v));
        }
        $payload['ComparisonOperator'] = $v;
        if (null !== $v = $this->TreatMissingData) {
            $payload['TreatMissingData'] = $v;
        }
        if (null !== $v = $this->EvaluateLowSampleCountPercentile) {
            $payload['EvaluateLowSampleCountPercentile'] = $v;
        }

        $index = 0;
        foreach ($this->Metrics as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["Metrics.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        $index = 0;
        foreach ($this->Tags as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["Tags.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        if (null !== $v = $this->ThresholdMetricId) {
            $payload['ThresholdMetricId'] = $v;
        }

        return $payload;
    }
}
