<?php

namespace AsyncAws\Sns;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Sns\Input\CreateTopicInput;
use AsyncAws\Sns\Input\DeleteTopicInput;
use AsyncAws\Sns\Input\ListSubscriptionsByTopicInput;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\Input\SubscribeInput;
use AsyncAws\Sns\Input\UnsubscribeInput;
use AsyncAws\Sns\Result\CreateTopicResponse;
use AsyncAws\Sns\Result\ListSubscriptionsByTopicResponse;
use AsyncAws\Sns\Result\PublishResponse;
use AsyncAws\Sns\Result\SubscribeResponse;
use AsyncAws\Sns\ValueObject\Subscription;

class SnsClient extends AbstractApi
{
    /**
     * Creates a topic to which notifications can be published. Users can create at most 100,000 topics. For more
     * information, see https://aws.amazon.com/sns. This action is idempotent, so if the requester already owns a topic with
     * the specified name, that topic's ARN is returned without creating a new topic.
     *
     * @see http://aws.amazon.com/sns/
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#createtopic
     *
     * @param array{
     *   Name: string,
     *   Attributes?: array<string, string>,
     *   Tags?: \AsyncAws\Sns\ValueObject\Tag[],
     *   @region?: string,
     * }|CreateTopicInput $input
     */
    public function createTopic($input): CreateTopicResponse
    {
        $input = CreateTopicInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateTopic', 'region' => $input->getRegion()]));

        return new CreateTopicResponse($response);
    }

    /**
     * Deletes a topic and all its subscriptions. Deleting a topic might prevent some messages previously sent to the topic
     * from being delivered to subscribers. This action is idempotent, so deleting a topic that does not exist does not
     * result in an error.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#deletetopic
     *
     * @param array{
     *   TopicArn: string,
     *   @region?: string,
     * }|DeleteTopicInput $input
     */
    public function deleteTopic($input): Result
    {
        $input = DeleteTopicInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteTopic', 'region' => $input->getRegion()]));

        return new Result($response);
    }

    /**
     * Returns a list of the subscriptions to a specific topic. Each call returns a limited list of subscriptions, up to
     * 100. If there are more subscriptions, a `NextToken` is also returned. Use the `NextToken` parameter in a new
     * `ListSubscriptionsByTopic` call to get further results.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#listsubscriptionsbytopic
     *
     * @param array{
     *   TopicArn: string,
     *   NextToken?: string,
     *   @region?: string,
     * }|ListSubscriptionsByTopicInput $input
     */
    public function listSubscriptionsByTopic($input): ListSubscriptionsByTopicResponse
    {
        $input = ListSubscriptionsByTopicInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListSubscriptionsByTopic', 'region' => $input->getRegion()]));

        return new ListSubscriptionsByTopicResponse($response, $this, $input);
    }

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
     *   MessageAttributes?: array<string, \AsyncAws\Sns\ValueObject\MessageAttributeValue>,
     *   @region?: string,
     * }|PublishInput $input
     */
    public function publish($input): PublishResponse
    {
        $input = PublishInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Publish', 'region' => $input->getRegion()]));

        return new PublishResponse($response);
    }

    /**
     * Prepares to subscribe an endpoint by sending the endpoint a confirmation message. To actually create a subscription,
     * the endpoint owner must call the `ConfirmSubscription` action with the token from the confirmation message.
     * Confirmation tokens are valid for three days.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#subscribe
     *
     * @param array{
     *   TopicArn: string,
     *   Protocol: string,
     *   Endpoint?: string,
     *   Attributes?: array<string, string>,
     *   ReturnSubscriptionArn?: bool,
     *   @region?: string,
     * }|SubscribeInput $input
     */
    public function subscribe($input): SubscribeResponse
    {
        $input = SubscribeInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Subscribe', 'region' => $input->getRegion()]));

        return new SubscribeResponse($response);
    }

    /**
     * Deletes a subscription. If the subscription requires authentication for deletion, only the owner of the subscription
     * or the topic's owner can unsubscribe, and an AWS signature is required. If the `Unsubscribe` call does not require
     * authentication and the requester is not the subscription owner, a final cancellation message is delivered to the
     * endpoint, so that the endpoint owner can easily resubscribe to the topic if the `Unsubscribe` request was unintended.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#unsubscribe
     *
     * @param array{
     *   SubscriptionArn: string,
     *   @region?: string,
     * }|UnsubscribeInput $input
     */
    public function unsubscribe($input): Result
    {
        $input = UnsubscribeInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Unsubscribe', 'region' => $input->getRegion()]));

        return new Result($response);
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
                    'endpoint' => "https://sns.$region.amazonaws.com",
                    'signRegion' => $region,
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://sns.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://sns.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-1':
                return [
                    'endpoint' => 'https://sns-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-east-2':
                return [
                    'endpoint' => 'https://sns-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-1':
                return [
                    'endpoint' => 'https://sns-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-west-2':
                return [
                    'endpoint' => 'https://sns-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://sns.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://sns.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
                return [
                    'endpoint' => 'https://sns.us-iso-east-1.c2s.ic.gov',
                    'signRegion' => 'us-iso-east-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(sprintf('The region "%s" is not supported by "Sns".', $region));
    }
}
