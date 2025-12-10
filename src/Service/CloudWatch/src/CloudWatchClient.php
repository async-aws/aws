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
use AsyncAws\CloudWatch\ValueObject\EntityMetricData;
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
     * You can use the `GetMetricData` API to retrieve CloudWatch metric values. The operation can also include a CloudWatch
     * Metrics Insights query, and one or more metric math functions.
     *
     * A `GetMetricData` operation that does not include a query can retrieve as many as 500 different metrics in a single
     * request, with a total of as many as 100,800 data points. You can also optionally perform metric math expressions on
     * the values of the returned statistics, to create new time series that represent new insights into your data. For
     * example, using Lambda metrics, you could divide the Errors metric by the Invocations metric to get an error rate time
     * series. For more information about metric math expressions, see Metric Math Syntax and Functions [^1] in the *Amazon
     * CloudWatch User Guide*.
     *
     * If you include a Metrics Insights query, each `GetMetricData` operation can include only one query. But the same
     * `GetMetricData` operation can also retrieve other metrics. Metrics Insights queries can query only the most recent
     * three hours of metric data. For more information about Metrics Insights, see Query your metrics with CloudWatch
     * Metrics Insights [^2].
     *
     * Calls to the `GetMetricData` API have a different pricing structure than calls to `GetMetricStatistics`. For more
     * information about pricing, see Amazon CloudWatch Pricing [^3].
     *
     * Amazon CloudWatch retains metric data as follows:
     *
     * - Data points with a period of less than 60 seconds are available for 3 hours. These data points are high-resolution
     *   metrics and are available only for custom metrics that have been defined with a `StorageResolution` of 1.
     * - Data points with a period of 60 seconds (1-minute) are available for 15 days.
     * - Data points with a period of 300 seconds (5-minute) are available for 63 days.
     * - Data points with a period of 3600 seconds (1 hour) are available for 455 days (15 months).
     *
     * Data points that are initially published with a shorter period are aggregated together for long-term storage. For
     * example, if you collect data using a period of 1 minute, the data remains available for 15 days with 1-minute
     * resolution. After 15 days, this data is still available, but is aggregated and retrievable only with a resolution of
     * 5 minutes. After 63 days, the data is further aggregated and is available with a resolution of 1 hour.
     *
     * If you omit `Unit` in your request, all data that was collected with any unit is returned, along with the
     * corresponding units that were specified when the data was reported to CloudWatch. If you specify a unit, the
     * operation returns only data that was collected with that unit specified. If you specify a unit that does not match
     * the data collected, the results of the operation are null. CloudWatch does not perform unit conversions.
     *
     * **Using Metrics Insights queries with metric math**
     *
     * You can't mix a Metric Insights query and metric math syntax in the same expression, but you can reference results
     * from a Metrics Insights query within other Metric math expressions. A Metrics Insights query without a **GROUP BY**
     * clause returns a single time-series (TS), and can be used as input for a metric math expression that expects a single
     * time series. A Metrics Insights query with a **GROUP BY** clause returns an array of time-series (TS[]), and can be
     * used as input for a metric math expression that expects an array of time series.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/using-metric-math.html#metric-math-syntax
     * [^2]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/query_with_cloudwatch-metrics-insights.html
     * [^3]: https://aws.amazon.com/cloudwatch/pricing/
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#getmetricdata
     *
     * @param array{
     *   MetricDataQueries: array<MetricDataQuery|array>,
     *   StartTime: \DateTimeImmutable|string,
     *   EndTime: \DateTimeImmutable|string,
     *   NextToken?: string|null,
     *   ScanBy?: ScanBy::*|null,
     *   MaxDatapoints?: int|null,
     *   LabelOptions?: LabelOptions|array|null,
     *   '@region'?: string|null,
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
     * The maximum number of data points returned from a single call is 1,440. If you request more than 1,440 data points,
     * CloudWatch returns an error. To reduce the number of data points, you can narrow the specified time range and make
     * multiple requests across adjacent time ranges, or you can increase the specified period. Data points are not returned
     * in chronological order.
     *
     * CloudWatch aggregates data points based on the length of the period that you specify. For example, if you request
     * statistics with a one-hour period, CloudWatch aggregates all data points with time stamps that fall within each
     * one-hour period. Therefore, the number of values aggregated by CloudWatch is larger than the number of data points
     * returned.
     *
     * CloudWatch needs raw data points to calculate percentile statistics. If you publish data using a statistic set
     * instead, you can only retrieve percentile statistics for this data if one of the following conditions is true:
     *
     * - The SampleCount value of the statistic set is 1.
     * - The Min and the Max values of the statistic set are equal.
     *
     * Percentile statistics are not available for metrics when any of the metric values are negative numbers.
     *
     * Amazon CloudWatch retains metric data as follows:
     *
     * - Data points with a period of less than 60 seconds are available for 3 hours. These data points are high-resolution
     *   metrics and are available only for custom metrics that have been defined with a `StorageResolution` of 1.
     * - Data points with a period of 60 seconds (1-minute) are available for 15 days.
     * - Data points with a period of 300 seconds (5-minute) are available for 63 days.
     * - Data points with a period of 3600 seconds (1 hour) are available for 455 days (15 months).
     *
     * Data points that are initially published with a shorter period are aggregated together for long-term storage. For
     * example, if you collect data using a period of 1 minute, the data remains available for 15 days with 1-minute
     * resolution. After 15 days, this data is still available, but is aggregated and retrievable only with a resolution of
     * 5 minutes. After 63 days, the data is further aggregated and is available with a resolution of 1 hour.
     *
     * CloudWatch started retaining 5-minute and 1-hour metric data as of July 9, 2016.
     *
     * For information about metrics and dimensions supported by Amazon Web Services services, see the Amazon CloudWatch
     * Metrics and Dimensions Reference [^1] in the *Amazon CloudWatch User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/CW_Support_For_AWS.html
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#getmetricstatistics
     *
     * @param array{
     *   Namespace: string,
     *   MetricName: string,
     *   Dimensions?: array<Dimension|array>|null,
     *   StartTime: \DateTimeImmutable|string,
     *   EndTime: \DateTimeImmutable|string,
     *   Period: int,
     *   Statistics?: array<Statistic::*>|null,
     *   ExtendedStatistics?: string[]|null,
     *   Unit?: StandardUnit::*|null,
     *   '@region'?: string|null,
     * }|GetMetricStatisticsInput $input
     *
     * @throws InternalServiceFaultException
     * @throws InvalidParameterCombinationException
     * @throws InvalidParameterValueException
     * @throws MissingRequiredParameterException
     */
    public function getMetricStatistics($input): GetMetricStatisticsOutput
    {
        $input = GetMetricStatisticsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetMetricStatistics', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServiceError' => InternalServiceFaultException::class,
            'InvalidParameterCombination' => InvalidParameterCombinationException::class,
            'InvalidParameterValue' => InvalidParameterValueException::class,
            'MissingParameter' => MissingRequiredParameterException::class,
        ]]));

        return new GetMetricStatisticsOutput($response);
    }

    /**
     * List the specified metrics. You can use the returned metrics with GetMetricData [^1] or GetMetricStatistics [^2] to
     * get statistical data.
     *
     * Up to 500 results are returned for any one call. To retrieve additional results, use the returned token with
     * subsequent calls.
     *
     * After you create a metric, allow up to 15 minutes for the metric to appear. To see metric statistics sooner, use
     * GetMetricData [^3] or GetMetricStatistics [^4].
     *
     * If you are using CloudWatch cross-account observability, you can use this operation in a monitoring account and view
     * metrics from the linked source accounts. For more information, see CloudWatch cross-account observability [^5].
     *
     * `ListMetrics` doesn't return information about metrics if those metrics haven't reported data in the past two weeks.
     * To retrieve those metrics, use GetMetricData [^6] or GetMetricStatistics [^7].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * [^2]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     * [^3]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * [^4]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     * [^5]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/CloudWatch-Unified-Cross-Account.html
     * [^6]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * [^7]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_ListMetrics.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#listmetrics
     *
     * @param array{
     *   Namespace?: string|null,
     *   MetricName?: string|null,
     *   Dimensions?: array<DimensionFilter|array>|null,
     *   NextToken?: string|null,
     *   RecentlyActive?: RecentlyActive::*|null,
     *   IncludeLinkedAccounts?: bool|null,
     *   OwningAccount?: string|null,
     *   '@region'?: string|null,
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
     * Publishes metric data to Amazon CloudWatch. CloudWatch associates the data with the specified metric. If the
     * specified metric does not exist, CloudWatch creates the metric. When CloudWatch creates a metric, it can take up to
     * fifteen minutes for the metric to appear in calls to ListMetrics [^1].
     *
     * You can publish metrics with associated entity data (so that related telemetry can be found and viewed together), or
     * publish metric data by itself. To send entity data with your metrics, use the `EntityMetricData` parameter. To send
     * metrics without entity data, use the `MetricData` parameter. The `EntityMetricData` structure includes `MetricData`
     * structures for the metric data.
     *
     * You can publish either individual values in the `Value` field, or arrays of values and the number of times each value
     * occurred during the period by using the `Values` and `Counts` fields in the `MetricData` structure. Using the
     * `Values` and `Counts` method enables you to publish up to 150 values per metric with one `PutMetricData` request, and
     * supports retrieving percentile statistics on this data.
     *
     * Each `PutMetricData` request is limited to 1 MB in size for HTTP POST requests. You can send a payload compressed by
     * gzip. Each request is also limited to no more than 1000 different metrics (across both the `MetricData` and
     * `EntityMetricData` properties).
     *
     * Although the `Value` parameter accepts numbers of type `Double`, CloudWatch rejects values that are either too small
     * or too large. Values must be in the range of -2^360 to 2^360. In addition, special values (for example, NaN,
     * +Infinity, -Infinity) are not supported.
     *
     * You can use up to 30 dimensions per metric to further clarify what data the metric collects. Each dimension consists
     * of a Name and Value pair. For more information about specifying dimensions, see Publishing Metrics [^2] in the
     * *Amazon CloudWatch User Guide*.
     *
     * You specify the time stamp to be associated with each data point. You can specify time stamps that are as much as two
     * weeks before the current date, and as much as 2 hours after the current day and time.
     *
     * Data points with time stamps from 24 hours ago or longer can take at least 48 hours to become available for
     * GetMetricData [^3] or GetMetricStatistics [^4] from the time they are submitted. Data points with time stamps between
     * 3 and 24 hours ago can take as much as 2 hours to become available for GetMetricData [^5] or GetMetricStatistics
     * [^6].
     *
     * CloudWatch needs raw data points to calculate percentile statistics. If you publish data using a statistic set
     * instead, you can only retrieve percentile statistics for this data if one of the following conditions is true:
     *
     * - The `SampleCount` value of the statistic set is 1 and `Min`, `Max`, and `Sum` are all equal.
     * - The `Min` and `Max` are equal, and `Sum` is equal to `Min` multiplied by `SampleCount`.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_ListMetrics.html
     * [^2]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/publishingMetrics.html
     * [^3]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * [^4]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     * [^5]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricData.html
     * [^6]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_GetMetricStatistics.html
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/APIReference/API_PutMetricData.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#putmetricdata
     *
     * @param array{
     *   Namespace: string,
     *   MetricData?: array<MetricDatum|array>|null,
     *   EntityMetricData?: array<EntityMetricData|array>|null,
     *   StrictEntityValidation?: bool|null,
     *   '@region'?: string|null,
     * }|PutMetricDataInput $input
     *
     * @throws InternalServiceFaultException
     * @throws InvalidParameterCombinationException
     * @throws InvalidParameterValueException
     * @throws MissingRequiredParameterException
     */
    public function putMetricData($input): Result
    {
        $input = PutMetricDataInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutMetricData', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalServiceError' => InternalServiceFaultException::class,
            'InvalidParameterCombination' => InvalidParameterCombinationException::class,
            'InvalidParameterValue' => InvalidParameterValueException::class,
            'MissingParameter' => MissingRequiredParameterException::class,
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
            case 'eusc-de-east-1':
                return [
                    'endpoint' => 'https://monitoring.eusc-de-east-1.amazonaws.eu',
                    'signRegion' => 'eusc-de-east-1',
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
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://monitoring.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
            case 'us-isob-west-1':
                return [
                    'endpoint' => "https://monitoring.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://monitoring.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'monitoring',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://monitoring.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
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
