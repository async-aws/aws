<?php

namespace AsyncAws\Sns;

use AsyncAws\Core\AbstractApi;
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
