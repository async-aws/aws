<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Input for Subscribe action.
 */
final class SubscribeInput extends Input
{
    /**
     * The ARN of the topic you want to subscribe to.
     *
     * @required
     *
     * @var string|null
     */
    private $topicArn;

    /**
     * The protocol that you want to use. Supported protocols include:
     *
     * - `http` – delivery of JSON-encoded message via HTTP POST
     * - `https` – delivery of JSON-encoded message via HTTPS POST
     * - `email` – delivery of message via SMTP
     * - `email-json` – delivery of JSON-encoded message via SMTP
     * - `sms` – delivery of message via SMS
     * - `sqs` – delivery of JSON-encoded message to an Amazon SQS queue
     * - `application` – delivery of JSON-encoded message to an EndpointArn for a mobile app and device
     * - `lambda` – delivery of JSON-encoded message to an Lambda function
     * - `firehose` – delivery of JSON-encoded message to an Amazon Kinesis Data Firehose delivery stream.
     *
     * @required
     *
     * @var string|null
     */
    private $protocol;

    /**
     * The endpoint that you want to receive notifications. Endpoints vary by protocol:
     *
     * - For the `http` protocol, the (public) endpoint is a URL beginning with `http://`.
     * - For the `https` protocol, the (public) endpoint is a URL beginning with `https://`.
     * - For the `email` protocol, the endpoint is an email address.
     * - For the `email-json` protocol, the endpoint is an email address.
     * - For the `sms` protocol, the endpoint is a phone number of an SMS-enabled device.
     * - For the `sqs` protocol, the endpoint is the ARN of an Amazon SQS queue.
     * - For the `application` protocol, the endpoint is the EndpointArn of a mobile app and device.
     * - For the `lambda` protocol, the endpoint is the ARN of an Lambda function.
     * - For the `firehose` protocol, the endpoint is the ARN of an Amazon Kinesis Data Firehose delivery stream.
     *
     * @var string|null
     */
    private $endpoint;

    /**
     * A map of attributes with their corresponding values.
     *
     * The following lists the names, descriptions, and values of the special request parameters that the `Subscribe` action
     * uses:
     *
     * - `DeliveryPolicy` – The policy that defines how Amazon SNS retries failed deliveries to HTTP/S endpoints.
     * - `FilterPolicy` – The simple JSON object that lets your subscriber receive only a subset of messages, rather than
     *   receiving every message published to the topic.
     * - `FilterPolicyScope` – This attribute lets you choose the filtering scope by using one of the following string
     *   value types:
     *
     *   - `MessageAttributes` (default) – The filter is applied on the message attributes.
     *   - `MessageBody` – The filter is applied on the message body.
     *
     * - `RawMessageDelivery` – When set to `true`, enables raw message delivery to Amazon SQS or HTTP/S endpoints. This
     *   eliminates the need for the endpoints to process JSON formatting, which is otherwise created for Amazon SNS
     *   metadata.
     * - `RedrivePolicy` – When specified, sends undeliverable messages to the specified Amazon SQS dead-letter queue.
     *   Messages that can't be delivered due to client errors (for example, when the subscribed endpoint is unreachable) or
     *   server errors (for example, when the service that powers the subscribed endpoint becomes unavailable) are held in
     *   the dead-letter queue for further analysis or reprocessing.
     *
     * The following attribute applies only to Amazon Data Firehose delivery stream subscriptions:
     *
     * - `SubscriptionRoleArn` – The ARN of the IAM role that has the following:
     *
     *   - Permission to write to the Firehose delivery stream
     *   - Amazon SNS listed as a trusted entity
     *
     *   Specifying a valid ARN for this attribute is required for Firehose delivery stream subscriptions. For more
     *   information, see Fanout to Firehose delivery streams [^1] in the *Amazon SNS Developer Guide*.
     *
     * The following attributes apply only to FIFO topics [^2]:
     *
     * - `ReplayPolicy` – Adds or updates an inline policy document for a subscription to replay messages stored in the
     *   specified Amazon SNS topic.
     * - `ReplayStatus` – Retrieves the status of the subscription message replay, which can be one of the following:
     *
     *   - `Completed` – The replay has successfully redelivered all messages, and is now delivering newly published
     *     messages. If an ending point was specified in the `ReplayPolicy` then the subscription will no longer receive
     *     newly published messages.
     *   - `In progress` – The replay is currently replaying the selected messages.
     *   - `Failed` – The replay was unable to complete.
     *   - `Pending` – The default state while the replay initiates.
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/sns-firehose-as-subscriber.html
     * [^2]: https://docs.aws.amazon.com/sns/latest/dg/sns-fifo-topics.html
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * Sets whether the response from the `Subscribe` request includes the subscription ARN, even if the subscription is not
     * yet confirmed.
     *
     * If you set this parameter to `true`, the response includes the ARN in all cases, even if the subscription is not yet
     * confirmed. In addition to the ARN for confirmed subscriptions, the response also includes the `pending subscription`
     * ARN value for subscriptions that aren't yet confirmed. A subscription becomes confirmed when the subscriber calls the
     * `ConfirmSubscription` action with a confirmation token.
     *
     * The default value is `false`.
     *
     * @var bool|null
     */
    private $returnSubscriptionArn;

    /**
     * @param array{
     *   TopicArn?: string,
     *   Protocol?: string,
     *   Endpoint?: null|string,
     *   Attributes?: null|array<string, string>,
     *   ReturnSubscriptionArn?: null|bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->topicArn = $input['TopicArn'] ?? null;
        $this->protocol = $input['Protocol'] ?? null;
        $this->endpoint = $input['Endpoint'] ?? null;
        $this->attributes = $input['Attributes'] ?? null;
        $this->returnSubscriptionArn = $input['ReturnSubscriptionArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TopicArn?: string,
     *   Protocol?: string,
     *   Endpoint?: null|string,
     *   Attributes?: null|array<string, string>,
     *   ReturnSubscriptionArn?: null|bool,
     *   '@region'?: string|null,
     * }|SubscribeInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function getProtocol(): ?string
    {
        return $this->protocol;
    }

    public function getReturnSubscriptionArn(): ?bool
    {
        return $this->returnSubscriptionArn;
    }

    public function getTopicArn(): ?string
    {
        return $this->topicArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'Subscribe', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, string> $value
     */
    public function setAttributes(array $value): self
    {
        $this->attributes = $value;

        return $this;
    }

    public function setEndpoint(?string $value): self
    {
        $this->endpoint = $value;

        return $this;
    }

    public function setProtocol(?string $value): self
    {
        $this->protocol = $value;

        return $this;
    }

    public function setReturnSubscriptionArn(?bool $value): self
    {
        $this->returnSubscriptionArn = $value;

        return $this;
    }

    public function setTopicArn(?string $value): self
    {
        $this->topicArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->topicArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "TopicArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TopicArn'] = $v;
        if (null === $v = $this->protocol) {
            throw new InvalidArgument(\sprintf('Missing parameter "Protocol" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Protocol'] = $v;
        if (null !== $v = $this->endpoint) {
            $payload['Endpoint'] = $v;
        }
        if (null !== $v = $this->attributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["Attributes.entry.$index.key"] = $mapKey;
                $payload["Attributes.entry.$index.value"] = $mapValue;
            }
        }
        if (null !== $v = $this->returnSubscriptionArn) {
            $payload['ReturnSubscriptionArn'] = $v ? 'true' : 'false';
        }

        return $payload;
    }
}
