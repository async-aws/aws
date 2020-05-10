<?php

namespace AsyncAws\EventBridge;

use AsyncAws\Core\AbstractApi;
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
