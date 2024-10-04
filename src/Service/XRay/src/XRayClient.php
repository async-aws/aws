<?php

namespace AsyncAws\XRay;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\XRay\Exception\InvalidRequestException;
use AsyncAws\XRay\Exception\ThrottledException;
use AsyncAws\XRay\Input\PutTraceSegmentsRequest;
use AsyncAws\XRay\Result\PutTraceSegmentsResult;

class XRayClient extends AbstractApi
{
    /**
     * Uploads segment documents to Amazon Web Services X-Ray. The X-Ray SDK [^1] generates segment documents and sends them
     * to the X-Ray daemon, which uploads them in batches. A segment document can be a completed segment, an in-progress
     * segment, or an array of subsegments.
     *
     * Segments must include the following fields. For the full segment document schema, see Amazon Web Services X-Ray
     * Segment Documents [^2] in the *Amazon Web Services X-Ray Developer Guide*.
     *
     * **Required segment document fields**
     *
     * - `name` - The name of the service that handled the request.
     * - `id` - A 64-bit identifier for the segment, unique among segments in the same trace, in 16 hexadecimal digits.
     * - `trace_id` - A unique identifier that connects all segments and subsegments originating from a single client
     *   request.
     * - `start_time` - Time the segment or subsegment was created, in floating point seconds in epoch time, accurate to
     *   milliseconds. For example, `1480615200.010` or `1.480615200010E9`.
     * - `end_time` - Time the segment or subsegment was closed. For example, `1480615200.090` or `1.480615200090E9`.
     *   Specify either an `end_time` or `in_progress`.
     * - `in_progress` - Set to `true` instead of specifying an `end_time` to record that a segment has been started, but is
     *   not complete. Send an in-progress segment when your application receives a request that will take a long time to
     *   serve, to trace that the request was received. When the response is sent, send the complete segment to overwrite
     *   the in-progress segment.
     *
     * A `trace_id` consists of three numbers separated by hyphens. For example, 1-58406520-a006649127e371903a2de979. This
     * includes:
     *
     * **Trace ID Format**
     *
     * - The version number, for instance, `1`.
     * - The time of the original request, in Unix epoch time, in 8 hexadecimal digits. For example, 10:00AM December 2nd,
     *   2016 PST in epoch time is `1480615200` seconds, or `58406520` in hexadecimal.
     * - A 96-bit identifier for the trace, globally unique, in 24 hexadecimal digits.
     *
     * [^1]: https://docs.aws.amazon.com/xray/index.html
     * [^2]: https://docs.aws.amazon.com/xray/latest/devguide/xray-api-segmentdocuments.html
     *
     * @see https://docs.aws.amazon.com/xray/latest/api/API_PutTraceSegments.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-xray-2016-04-12.html#puttracesegments
     *
     * @param array{
     *   TraceSegmentDocuments: string[],
     *   '@region'?: string|null,
     * }|PutTraceSegmentsRequest $input
     *
     * @throws InvalidRequestException
     * @throws ThrottledException
     */
    public function putTraceSegments($input): PutTraceSegmentsResult
    {
        $input = PutTraceSegmentsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutTraceSegments', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidRequestException' => InvalidRequestException::class,
            'ThrottledException' => ThrottledException::class,
        ]]));

        return new PutTraceSegmentsResult($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRestAwsErrorFactory();
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
                    'endpoint' => "https://xray.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://xray-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://xray-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://xray-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://xray-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://xray-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://xray-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://xray.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'xray',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://xray.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'xray',
            'signVersions' => ['v4'],
        ];
    }
}
