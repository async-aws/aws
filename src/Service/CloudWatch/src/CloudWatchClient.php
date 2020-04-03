<?php

namespace AsyncAws\CloudWatch;

use AsyncAws\CloudWatch\Input\PutMetricAlarmInput;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;

class CloudWatchClient extends AbstractApi
{
    /**
     * Creates or updates an alarm and associates it with the specified metric, metric math expression, or anomaly detection
     * model.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#putmetricalarm
     *
     * @param array{
     *   AlarmName: string,
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
     *   EvaluationPeriods: int,
     *   DatapointsToAlarm?: int,
     *   Threshold?: float,
     *   ComparisonOperator: \AsyncAws\CloudWatch\Enum\ComparisonOperator::*,
     *   TreatMissingData?: string,
     *   EvaluateLowSampleCountPercentile?: string,
     *   Metrics?: \AsyncAws\CloudWatch\ValueObject\MetricDataQuery[],
     *   Tags?: \AsyncAws\CloudWatch\ValueObject\Tag[],
     *   ThresholdMetricId?: string,
     * }|PutMetricAlarmInput $input
     */
    public function putMetricAlarm($input): Result
    {
        $response = $this->getResponse(PutMetricAlarmInput::create($input)->request(), new RequestContext(['operation' => 'PutMetricAlarm']));

        return new Result($response);
    }

    protected function getServiceCode(): string
    {
        return 'monitoring';
    }

    protected function getSignatureScopeName(): string
    {
        return 'monitoring';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
