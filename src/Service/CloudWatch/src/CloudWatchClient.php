<?php

namespace AsyncAws\CloudWatch;

use AsyncAws\CloudWatch\Enum\RecentlyActive;
use AsyncAws\CloudWatch\Enum\ScanBy;
use AsyncAws\CloudWatch\Enum\StandardUnit;
use AsyncAws\CloudWatch\Enum\Statistic;
use AsyncAws\CloudWatch\Exception\InternalServiceFaultException;
use AsyncAws\CloudWatch\Exception\InvalidNextTokenException;
use AsyncAws\CloudWatch\Exception\InvalidParameterCombinationException;
use AsyncAws\CloudWatch\Exception\InvalidParameterValueException;
use AsyncAws\CloudWatch\Exception\MissingRequiredParameterException;
use AsyncAws\CloudWatch\Input\GetMetricDataInput;
use AsyncAws\CloudWatch\Input\GetMetricStatisticsInput;
use AsyncAws\CloudWatch\Input\ListMetricsInput;
use AsyncAws\CloudWatch\Input\PutMetricDataInput;
use AsyncAws\CloudWatch\Result\GetMetricDataOutput;
use AsyncAws\CloudWatch\Result\GetMetricStatisticsOutput;
use AsyncAws\CloudWatch\Result\ListMetricsOutput;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\CloudWatch\ValueObject\DimensionFilter;
use AsyncAws\CloudWatch\ValueObject\LabelOptions;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use AsyncAws\CloudWatch\ValueObject\MetricDatum;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;

class CloudWatchClient extends AbstractApi
{
    /**
     * You can use the `GetMetricData` API to retrieve as many as 500 different metrics in a single request, with a total of
     * as many as 100,800 data points. You can also optionally perform math expressions on the values of the returned
     * statistics, to create new time series that represent new insights into your data. For example, using Lambda metrics,
     * you could divide the Errors metric by the Invocations metric to get an error rate time series. For more information
     * about metric math expressions, see Metric Math Syntax and Functions in the *Amazon CloudWatch User Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/using-metric-math.html#metric-math-syntax
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#getmetricdata
     *
     * @param array{
     *   MetricDataQueries: MetricDataQuery[],
     *   StartTime: \DateTimeImmutable|string,
     *   EndTime: \DateTimeImmutable|string,
     *   NextToken?: string,
     *   ScanBy?: ScanBy::*,
     *   MaxDatapoints?: int,
     *   LabelOptions?: LabelOptions|array,
     *   @region?: string,
     * }|GetMetricDataInput $input
     *
     * @throws InvalidNextTokenException
     */
    public function getMetricData($input): GetMetricDataOutput
    {
        $input = GetMetricDataInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetMetricData', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidNextToken' => InvalidNextTokenException::class,
        ]]));

        return new GetMetricDataOutput($response, $this, $input);
    }

    /**
     * Gets statistics for the specified metric.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#getmetricstatistics
     *
     * @param array{
     *   Namespace: string,
     *   MetricName: string,
     *   Dimensions?: Dimension[],
     *   StartTime: \DateTimeImmutable|string,
     *   EndTime: \DateTimeImmutable|string,
     *   Period: int,
     *   Statistics?: list<Statistic::*>,
     *   ExtendedStatistics?: string[],
     *   Unit?: StandardUnit::*,
     *   @region?: string,
     * }|GetMetricStatisticsInput $input
     *
     * @throws InvalidParameterValueException
     * @throws MissingRequiredParameterException
     * @throws InvalidParameterCombinationException
     * @throws InternalServiceFaultException
     */
    public function getMetricStatistics($input): GetMetricStatisticsOutput
    {
        $input = GetMetricStatisticsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetMetricStatistics', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValue' => InvalidParameterValueException::class,
            'MissingParameter' => MissingRequiredParameterException::class,
            'InvalidParameterCombination' => InvalidParameterCombinationException::class,
            'InternalServiceError' => InternalServiceFaultException::class,
        ]]));

        return new GetMetricStatisticsOutput($response);
    }

    /**
     * List the specified metrics. You can use the returned metrics with GetMetricData or GetMetricStatistics to obtain
     * statistical data.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_ListMetrics.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#listmetrics
     *
     * @param array{
     *   Namespace?: string,
     *   MetricName?: string,
     *   Dimensions?: DimensionFilter[],
     *   NextToken?: string,
     *   RecentlyActive?: RecentlyActive::*,
     *   @region?: string,
     * }|ListMetricsInput $input
     *
     * @throws InternalServiceFaultException
     * @throws InvalidParameterValueException
     */
    public function listMetrics($input = []): ListMetricsOutput
    {
        $input = ListMetricsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListMetrics', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServiceError' => InternalServiceFaultException::class,
            'InvalidParameterValue' => InvalidParameterValueException::class,
        ]]));

        return new ListMetricsOutput($response, $this, $input);
    }

    /**
     * Publishes metric data points to Amazon CloudWatch. CloudWatch associates the data points with the specified metric.
     * If the specified metric does not exist, CloudWatch creates the metric. When CloudWatch creates a metric, it can take
     * up to fifteen minutes for the metric to appear in calls to ListMetrics.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_ListMetrics.html
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_PutMetricData.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#putmetricdata
     *
     * @param array{
     *   Namespace: string,
     *   MetricData: MetricDatum[],
     *   @region?: string,
     * }|PutMetricDataInput $input
     *
     * @throws InvalidParameterValueException
     * @throws MissingRequiredParameterException
     * @throws InvalidParameterCombinationException
     * @throws InternalServiceFaultException
     */
    public function putMetricData($input): Result
    {
        $input = PutMetricDataInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutMetricData', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameterValue' => InvalidParameterValueException::class,
            'MissingParameter' => MissingRequiredParameterException::class,
            'InvalidParameterCombination' => InvalidParameterCombinationException::class,
            'InternalServiceError' => InternalServiceFaultException::class,
        ]]));

        return new Result($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new XmlAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://monitoring.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://monitoring.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://monitoring.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://monitoring-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://monitoring-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://monitoring.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://monitoring.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://monitoring-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://monitoring-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://monitoring.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'monitoring',
            'signVersions' => ['v4'],
        ];
    }
}
