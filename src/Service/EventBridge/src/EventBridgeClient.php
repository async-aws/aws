<?php

namespace AsyncAws\EventBridge;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;
use AsyncAws\EventBridge\Exception\InternalException;
use AsyncAws\EventBridge\Input\PutEventsRequest;
use AsyncAws\EventBridge\Result\PutEventsResponse;
use AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry;

class EventBridgeClient extends AbstractApi
{
    /**
     * Sends custom events to Amazon EventBridge so that they can be matched to rules.
     *
     * @see https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-events-2015-10-07.html#putevents
     *
     * @param array{
     *   Entries: PutEventsRequestEntry[],
     *   @region?: string,
     * }|PutEventsRequest $input
     *
     * @throws InternalException
     */
    public function putEvents($input): PutEventsResponse
    {
        $input = PutEventsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutEvents', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InternalException' => InternalException::class,
        ]]));

        return new PutEventsResponse($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
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
                    'endpoint' => "https://events.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://events.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://events.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://events-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://events-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-east-1':
                return [
                    'endpoint' => 'https://events.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
                return [
                    'endpoint' => 'https://events.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://events-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://events-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'events',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://events.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'events',
            'signVersions' => ['v4'],
        ];
    }
}
