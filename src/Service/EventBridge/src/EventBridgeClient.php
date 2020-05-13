<?php

namespace AsyncAws\EventBridge;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\EventBridge\Input\PutEventsRequest;
use AsyncAws\EventBridge\Result\PutEventsResponse;

class EventBridgeClient extends AbstractApi
{
    /**
     * Sends custom events to Amazon EventBridge so that they can be matched to rules.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-events-2015-10-07.html#putevents
     *
     * @param array{
     *   Entries: \AsyncAws\EventBridge\ValueObject\PutEventsRequestEntry[],
     *   @region?: string,
     * }|PutEventsRequest $input
     */
    public function putEvents($input): PutEventsResponse
    {
        $input = PutEventsRequest::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutEvents', 'region' => $input->getRegion()]));

        return new PutEventsResponse($response);
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-south-1':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ca-central-1':
            case 'eu-central-1':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'me-south-1':
            case 'sa-east-1':
            case 'us-east-1':
            case 'us-east-2':
            case 'us-west-1':
            case 'us-west-2':
                return [
                    'endpoint' => 'https://events.%region%.amazonaws.com',
                    'signRegion' => $region,
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => 'https://events.%region%.amazonaws.com.cn',
                    'signRegion' => $region,
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://events.%region%.c2s.ic.gov',
                    'signRegion' => $region,
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://events.%region%.sc2s.sgov.gov',
                    'signRegion' => $region,
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://events-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://events-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://events-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://events-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://events.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://events.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'events',
                    'signVersions' => [
                        0 => 'v4',
                    ],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "EventBridge".', $region));
    }

    protected function getServiceCode(): string
    {
        return 'events';
    }

    protected function getSignatureScopeName(): string
    {
        return 'events';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
