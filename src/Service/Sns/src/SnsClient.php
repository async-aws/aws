<?php

namespace AsyncAws\Sns;

use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use AsyncAws\Core\RequestContext;
use AsyncAws\Core\Result;
use AsyncAws\Sns\Exception\AuthorizationErrorException;
use AsyncAws\Sns\Exception\BatchEntryIdsNotDistinctException;
use AsyncAws\Sns\Exception\BatchRequestTooLongException;
use AsyncAws\Sns\Exception\ConcurrentAccessException;
use AsyncAws\Sns\Exception\EmptyBatchRequestException;
use AsyncAws\Sns\Exception\EndpointDisabledException;
use AsyncAws\Sns\Exception\FilterPolicyLimitExceededException;
use AsyncAws\Sns\Exception\InternalErrorException;
use AsyncAws\Sns\Exception\InvalidBatchEntryIdException;
use AsyncAws\Sns\Exception\InvalidParameterException;
use AsyncAws\Sns\Exception\InvalidParameterValueException;
use AsyncAws\Sns\Exception\InvalidSecurityException;
use AsyncAws\Sns\Exception\InvalidStateException;
use AsyncAws\Sns\Exception\KMSAccessDeniedException;
use AsyncAws\Sns\Exception\KMSDisabledException;
use AsyncAws\Sns\Exception\KMSInvalidStateException;
use AsyncAws\Sns\Exception\KMSNotFoundException;
use AsyncAws\Sns\Exception\KMSOptInRequiredException;
use AsyncAws\Sns\Exception\KMSThrottlingException;
use AsyncAws\Sns\Exception\NotFoundException;
use AsyncAws\Sns\Exception\PlatformApplicationDisabledException;
use AsyncAws\Sns\Exception\ReplayLimitExceededException;
use AsyncAws\Sns\Exception\StaleTagException;
use AsyncAws\Sns\Exception\SubscriptionLimitExceededException;
use AsyncAws\Sns\Exception\TagLimitExceededException;
use AsyncAws\Sns\Exception\TagPolicyException;
use AsyncAws\Sns\Exception\TooManyEntriesInBatchRequestException;
use AsyncAws\Sns\Exception\TopicLimitExceededException;
use AsyncAws\Sns\Exception\ValidationException;
use AsyncAws\Sns\Input\CreatePlatformEndpointInput;
use AsyncAws\Sns\Input\CreateTopicInput;
use AsyncAws\Sns\Input\DeleteEndpointInput;
use AsyncAws\Sns\Input\DeleteTopicInput;
use AsyncAws\Sns\Input\ListSubscriptionsByTopicInput;
use AsyncAws\Sns\Input\ListTopicsInput;
use AsyncAws\Sns\Input\PublishBatchInput;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\Input\SubscribeInput;
use AsyncAws\Sns\Input\UnsubscribeInput;
use AsyncAws\Sns\Result\CreateEndpointResponse;
use AsyncAws\Sns\Result\CreateTopicResponse;
use AsyncAws\Sns\Result\ListSubscriptionsByTopicResponse;
use AsyncAws\Sns\Result\ListTopicsResponse;
use AsyncAws\Sns\Result\PublishBatchResponse;
use AsyncAws\Sns\Result\PublishResponse;
use AsyncAws\Sns\Result\SubscribeResponse;
use AsyncAws\Sns\ValueObject\MessageAttributeValue;
use AsyncAws\Sns\ValueObject\PublishBatchRequestEntry;
use AsyncAws\Sns\ValueObject\Tag;

class SnsClient extends AbstractApi
{
    /**
     * Creates an endpoint for a device and mobile app on one of the supported push notification services, such as GCM
     * (Firebase Cloud Messaging) and APNS. `CreatePlatformEndpoint` requires the `PlatformApplicationArn` that is returned
     * from `CreatePlatformApplication`. You can use the returned `EndpointArn` to send a message to a mobile app or by the
     * `Subscribe` action for subscription to a topic. The `CreatePlatformEndpoint` action is idempotent, so if the
     * requester already owns an endpoint with the same device token and attributes, that endpoint's ARN is returned without
     * creating a new endpoint. For more information, see Using Amazon SNS Mobile Push Notifications [^1].
     *
     * When using `CreatePlatformEndpoint` with Baidu, two attributes must be provided: ChannelId and UserId. The token
     * field must also contain the ChannelId. For more information, see Creating an Amazon SNS Endpoint for Baidu [^2].
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/SNSMobilePush.html
     * [^2]: https://docs.aws.amazon.com/sns/latest/dg/SNSMobilePushBaiduEndpoint.html
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_CreatePlatformEndpoint.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#createplatformendpoint
     *
     * @param array{
     *   PlatformApplicationArn: string,
     *   Token: string,
     *   CustomUserData?: null|string,
     *   Attributes?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|CreatePlatformEndpointInput $input
     *
     * @throws InvalidParameterException
     * @throws InternalErrorException
     * @throws AuthorizationErrorException
     * @throws NotFoundException
     */
    public function createPlatformEndpoint($input): CreateEndpointResponse
    {
        $input = CreatePlatformEndpointInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreatePlatformEndpoint', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'InternalError' => InternalErrorException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
            'NotFound' => NotFoundException::class,
        ]]));

        return new CreateEndpointResponse($response);
    }

    /**
     * Creates a topic to which notifications can be published. Users can create at most 100,000 standard topics (at most
     * 1,000 FIFO topics). For more information, see Creating an Amazon SNS topic [^1] in the *Amazon SNS Developer Guide*.
     * This action is idempotent, so if the requester already owns a topic with the specified name, that topic's ARN is
     * returned without creating a new topic.
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/sns-create-topic.html
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_CreateTopic.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#createtopic
     *
     * @param array{
     *   Name: string,
     *   Attributes?: null|array<string, string>,
     *   Tags?: null|array<Tag|array>,
     *   DataProtectionPolicy?: null|string,
     *   '@region'?: string|null,
     * }|CreateTopicInput $input
     *
     * @throws InvalidParameterException
     * @throws TopicLimitExceededException
     * @throws InternalErrorException
     * @throws AuthorizationErrorException
     * @throws InvalidSecurityException
     * @throws TagLimitExceededException
     * @throws StaleTagException
     * @throws TagPolicyException
     * @throws ConcurrentAccessException
     */
    public function createTopic($input): CreateTopicResponse
    {
        $input = CreateTopicInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateTopic', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'TopicLimitExceeded' => TopicLimitExceededException::class,
            'InternalError' => InternalErrorException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'TagLimitExceeded' => TagLimitExceededException::class,
            'StaleTag' => StaleTagException::class,
            'TagPolicy' => TagPolicyException::class,
            'ConcurrentAccess' => ConcurrentAccessException::class,
        ]]));

        return new CreateTopicResponse($response);
    }

    /**
     * Deletes the endpoint for a device and mobile app from Amazon SNS. This action is idempotent. For more information,
     * see Using Amazon SNS Mobile Push Notifications [^1].
     *
     * When you delete an endpoint that is also subscribed to a topic, then you must also unsubscribe the endpoint from the
     * topic.
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/SNSMobilePush.html
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_DeleteEndpoint.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#deleteendpoint
     *
     * @param array{
     *   EndpointArn: string,
     *   '@region'?: string|null,
     * }|DeleteEndpointInput $input
     *
     * @throws InvalidParameterException
     * @throws InternalErrorException
     * @throws AuthorizationErrorException
     */
    public function deleteEndpoint($input): Result
    {
        $input = DeleteEndpointInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteEndpoint', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'InternalError' => InternalErrorException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Deletes a topic and all its subscriptions. Deleting a topic might prevent some messages previously sent to the topic
     * from being delivered to subscribers. This action is idempotent, so deleting a topic that does not exist does not
     * result in an error.
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_DeleteTopic.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#deletetopic
     *
     * @param array{
     *   TopicArn: string,
     *   '@region'?: string|null,
     * }|DeleteTopicInput $input
     *
     * @throws InvalidParameterException
     * @throws InvalidStateException
     * @throws InternalErrorException
     * @throws AuthorizationErrorException
     * @throws NotFoundException
     * @throws StaleTagException
     * @throws TagPolicyException
     * @throws ConcurrentAccessException
     */
    public function deleteTopic($input): Result
    {
        $input = DeleteTopicInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'DeleteTopic', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'InvalidState' => InvalidStateException::class,
            'InternalError' => InternalErrorException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
            'NotFound' => NotFoundException::class,
            'StaleTag' => StaleTagException::class,
            'TagPolicy' => TagPolicyException::class,
            'ConcurrentAccess' => ConcurrentAccessException::class,
        ]]));

        return new Result($response);
    }

    /**
     * Returns a list of the subscriptions to a specific topic. Each call returns a limited list of subscriptions, up to
     * 100. If there are more subscriptions, a `NextToken` is also returned. Use the `NextToken` parameter in a new
     * `ListSubscriptionsByTopic` call to get further results.
     *
     * This action is throttled at 30 transactions per second (TPS).
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_ListSubscriptionsByTopic.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#listsubscriptionsbytopic
     *
     * @param array{
     *   TopicArn: string,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * }|ListSubscriptionsByTopicInput $input
     *
     * @throws InvalidParameterException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws AuthorizationErrorException
     */
    public function listSubscriptionsByTopic($input): ListSubscriptionsByTopicResponse
    {
        $input = ListSubscriptionsByTopicInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListSubscriptionsByTopic', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'InternalError' => InternalErrorException::class,
            'NotFound' => NotFoundException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
        ]]));

        return new ListSubscriptionsByTopicResponse($response, $this, $input);
    }

    /**
     * Returns a list of the requester's topics. Each call returns a limited list of topics, up to 100. If there are more
     * topics, a `NextToken` is also returned. Use the `NextToken` parameter in a new `ListTopics` call to get further
     * results.
     *
     * This action is throttled at 30 transactions per second (TPS).
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_ListTopics.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#listtopics
     *
     * @param array{
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * }|ListTopicsInput $input
     *
     * @throws InvalidParameterException
     * @throws InternalErrorException
     * @throws AuthorizationErrorException
     */
    public function listTopics($input = []): ListTopicsResponse
    {
        $input = ListTopicsInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'ListTopics', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'InternalError' => InternalErrorException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
        ]]));

        return new ListTopicsResponse($response, $this, $input);
    }

    /**
     * Sends a message to an Amazon SNS topic, a text message (SMS message) directly to a phone number, or a message to a
     * mobile platform endpoint (when you specify the `TargetArn`).
     *
     * If you send a message to a topic, Amazon SNS delivers the message to each endpoint that is subscribed to the topic.
     * The format of the message depends on the notification protocol for each subscribed endpoint.
     *
     * When a `messageId` is returned, the message is saved and Amazon SNS immediately delivers it to subscribers.
     *
     * To use the `Publish` action for publishing a message to a mobile endpoint, such as an app on a Kindle device or
     * mobile phone, you must specify the EndpointArn for the TargetArn parameter. The EndpointArn is returned when making a
     * call with the `CreatePlatformEndpoint` action.
     *
     * For more information about formatting messages, see Send Custom Platform-Specific Payloads in Messages to Mobile
     * Devices [^1].
     *
     * ! You can publish messages only to topics and endpoints in the same Amazon Web Services Region.
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/mobile-push-send-custommessage.html
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_Publish.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#publish
     *
     * @param array{
     *   TopicArn?: null|string,
     *   TargetArn?: null|string,
     *   PhoneNumber?: null|string,
     *   Message: string,
     *   Subject?: null|string,
     *   MessageStructure?: null|string,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     *   '@region'?: string|null,
     * }|PublishInput $input
     *
     * @throws InvalidParameterException
     * @throws InvalidParameterValueException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws EndpointDisabledException
     * @throws PlatformApplicationDisabledException
     * @throws AuthorizationErrorException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSNotFoundException
     * @throws KMSOptInRequiredException
     * @throws KMSThrottlingException
     * @throws KMSAccessDeniedException
     * @throws InvalidSecurityException
     * @throws ValidationException
     */
    public function publish($input): PublishResponse
    {
        $input = PublishInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Publish', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'ParameterValueInvalid' => InvalidParameterValueException::class,
            'InternalError' => InternalErrorException::class,
            'NotFound' => NotFoundException::class,
            'EndpointDisabled' => EndpointDisabledException::class,
            'PlatformApplicationDisabled' => PlatformApplicationDisabledException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
            'KMSDisabled' => KMSDisabledException::class,
            'KMSInvalidState' => KMSInvalidStateException::class,
            'KMSNotFound' => KMSNotFoundException::class,
            'KMSOptInRequired' => KMSOptInRequiredException::class,
            'KMSThrottling' => KMSThrottlingException::class,
            'KMSAccessDenied' => KMSAccessDeniedException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new PublishResponse($response);
    }

    /**
     * Publishes up to ten messages to the specified topic. This is a batch version of `Publish`. For FIFO topics, multiple
     * messages within a single batch are published in the order they are sent, and messages are deduplicated within the
     * batch and across batches for 5 minutes.
     *
     * The result of publishing each message is reported individually in the response. Because the batch request can result
     * in a combination of successful and unsuccessful actions, you should check for batch errors even when the call returns
     * an HTTP status code of `200`.
     *
     * The maximum allowed individual message size and the maximum total payload size (the sum of the individual lengths of
     * all of the batched messages) are both 256 KB (262,144 bytes).
     *
     * Some actions take lists of parameters. These lists are specified using the `param.n` notation. Values of `n` are
     * integers starting from 1. For example, a parameter list with two elements looks like this:
     *
     * &AttributeName.1=first
     *
     * &AttributeName.2=second
     *
     * If you send a batch message to a topic, Amazon SNS publishes the batch message to each endpoint that is subscribed to
     * the topic. The format of the batch message depends on the notification protocol for each subscribed endpoint.
     *
     * When a `messageId` is returned, the batch message is saved and Amazon SNS immediately delivers the message to
     * subscribers.
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_PublishBatch.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#publishbatch
     *
     * @param array{
     *   TopicArn: string,
     *   PublishBatchRequestEntries: array<PublishBatchRequestEntry|array>,
     *   '@region'?: string|null,
     * }|PublishBatchInput $input
     *
     * @throws InvalidParameterException
     * @throws InvalidParameterValueException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws EndpointDisabledException
     * @throws PlatformApplicationDisabledException
     * @throws AuthorizationErrorException
     * @throws BatchEntryIdsNotDistinctException
     * @throws BatchRequestTooLongException
     * @throws EmptyBatchRequestException
     * @throws InvalidBatchEntryIdException
     * @throws TooManyEntriesInBatchRequestException
     * @throws KMSDisabledException
     * @throws KMSInvalidStateException
     * @throws KMSNotFoundException
     * @throws KMSOptInRequiredException
     * @throws KMSThrottlingException
     * @throws KMSAccessDeniedException
     * @throws InvalidSecurityException
     * @throws ValidationException
     */
    public function publishBatch($input): PublishBatchResponse
    {
        $input = PublishBatchInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PublishBatch', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'ParameterValueInvalid' => InvalidParameterValueException::class,
            'InternalError' => InternalErrorException::class,
            'NotFound' => NotFoundException::class,
            'EndpointDisabled' => EndpointDisabledException::class,
            'PlatformApplicationDisabled' => PlatformApplicationDisabledException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
            'BatchEntryIdsNotDistinct' => BatchEntryIdsNotDistinctException::class,
            'BatchRequestTooLong' => BatchRequestTooLongException::class,
            'EmptyBatchRequest' => EmptyBatchRequestException::class,
            'InvalidBatchEntryId' => InvalidBatchEntryIdException::class,
            'TooManyEntriesInBatchRequest' => TooManyEntriesInBatchRequestException::class,
            'KMSDisabled' => KMSDisabledException::class,
            'KMSInvalidState' => KMSInvalidStateException::class,
            'KMSNotFound' => KMSNotFoundException::class,
            'KMSOptInRequired' => KMSOptInRequiredException::class,
            'KMSThrottling' => KMSThrottlingException::class,
            'KMSAccessDenied' => KMSAccessDeniedException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
            'ValidationException' => ValidationException::class,
        ]]));

        return new PublishBatchResponse($response);
    }

    /**
     * Subscribes an endpoint to an Amazon SNS topic. If the endpoint type is HTTP/S or email, or if the endpoint and the
     * topic are not in the same Amazon Web Services account, the endpoint owner must run the `ConfirmSubscription` action
     * to confirm the subscription.
     *
     * You call the `ConfirmSubscription` action with the token from the subscription response. Confirmation tokens are
     * valid for two days.
     *
     * This action is throttled at 100 transactions per second (TPS).
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_Subscribe.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#subscribe
     *
     * @param array{
     *   TopicArn: string,
     *   Protocol: string,
     *   Endpoint?: null|string,
     *   Attributes?: null|array<string, string>,
     *   ReturnSubscriptionArn?: null|bool,
     *   '@region'?: string|null,
     * }|SubscribeInput $input
     *
     * @throws SubscriptionLimitExceededException
     * @throws FilterPolicyLimitExceededException
     * @throws ReplayLimitExceededException
     * @throws InvalidParameterException
     * @throws InternalErrorException
     * @throws NotFoundException
     * @throws AuthorizationErrorException
     * @throws InvalidSecurityException
     */
    public function subscribe($input): SubscribeResponse
    {
        $input = SubscribeInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Subscribe', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'SubscriptionLimitExceeded' => SubscriptionLimitExceededException::class,
            'FilterPolicyLimitExceeded' => FilterPolicyLimitExceededException::class,
            'ReplayLimitExceeded' => ReplayLimitExceededException::class,
            'InvalidParameter' => InvalidParameterException::class,
            'InternalError' => InternalErrorException::class,
            'NotFound' => NotFoundException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
        ]]));

        return new SubscribeResponse($response);
    }

    /**
     * Deletes a subscription. If the subscription requires authentication for deletion, only the owner of the subscription
     * or the topic's owner can unsubscribe, and an Amazon Web Services signature is required. If the `Unsubscribe` call
     * does not require authentication and the requester is not the subscription owner, a final cancellation message is
     * delivered to the endpoint, so that the endpoint owner can easily resubscribe to the topic if the `Unsubscribe`
     * request was unintended.
     *
     * > Amazon SQS queue subscriptions require authentication for deletion. Only the owner of the subscription, or the
     * > owner of the topic can unsubscribe using the required Amazon Web Services signature.
     *
     * This action is throttled at 100 transactions per second (TPS).
     *
     * @see https://docs.aws.amazon.com/sns/latest/api/API_Unsubscribe.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sns-2010-03-31.html#unsubscribe
     *
     * @param array{
     *   SubscriptionArn: string,
     *   '@region'?: string|null,
     * }|UnsubscribeInput $input
     *
     * @throws InvalidParameterException
     * @throws InternalErrorException
     * @throws AuthorizationErrorException
     * @throws NotFoundException
     * @throws InvalidSecurityException
     */
    public function unsubscribe($input): Result
    {
        $input = UnsubscribeInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'Unsubscribe', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidParameter' => InvalidParameterException::class,
            'InternalError' => InternalErrorException::class,
            'AuthorizationError' => AuthorizationErrorException::class,
            'NotFound' => NotFoundException::class,
            'InvalidSecurity' => InvalidSecurityException::class,
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
            case 'af-south-1':
            case 'ap-east-1':
            case 'ap-northeast-1':
            case 'ap-northeast-2':
            case 'ap-northeast-3':
            case 'ap-south-1':
            case 'ap-south-2':
            case 'ap-southeast-1':
            case 'ap-southeast-2':
            case 'ap-southeast-3':
            case 'ap-southeast-4':
            case 'ap-southeast-5':
            case 'ap-southeast-7':
            case 'ca-central-1':
            case 'ca-west-1':
            case 'eu-central-1':
            case 'eu-central-2':
            case 'eu-north-1':
            case 'eu-south-1':
            case 'eu-south-2':
            case 'eu-west-1':
            case 'eu-west-2':
            case 'eu-west-3':
            case 'il-central-1':
            case 'me-central-1':
            case 'me-south-1':
            case 'mx-central-1':
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
            case 'fips-ca-west-1':
                return [
                    'endpoint' => 'https://sns-fips.ca-west-1.amazonaws.com',
                    'signRegion' => 'ca-west-1',
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
            case 'fips-us-gov-east-1':
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://sns.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'fips-us-gov-west-1':
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://sns.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://sns.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://sns.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => 'https://sns.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
                    'signService' => 'sns',
                    'signVersions' => ['v4'],
                ];
        }

        throw new UnsupportedRegion(\sprintf('The region "%s" is not supported by "Sns".', $region));
    }
}
