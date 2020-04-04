<?php

namespace AsyncAws\Sns;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\Result\PublishResponse;

class SnsClient extends AbstractApi
{
    /**
     * Sends a message to an Amazon SNS topic or sends a text message (SMS message) directly to a phone number.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#publish
     *
     * @param array{
     *   TopicArn?: string,
     *   TargetArn?: string,
     *   PhoneNumber?: string,
     *   Message: string,
     *   Subject?: string,
     *   MessageStructure?: string,
     *   MessageAttributes?: \AsyncAws\Sns\ValueObject\MessageAttributeValue[],
     *   @region?: string,
     * }|PublishInput $input
     */
    public function publish($input): PublishResponse
    {
        $input = PublishInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Publish', 'region' => $input->getRegion()]));

        return new PublishResponse($response);
    }

    protected function getServiceCode(): string
    {
        return 'sns';
    }

    protected function getSignatureScopeName(): string
    {
        return 'sns';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
