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
     * The protocol that you want to use. Supported protocols include:.
     *
     * @required
     *
     * @var string|null
     */
    private $protocol;

    /**
     * The endpoint that you want to receive notifications. Endpoints vary by protocol:.
     *
     * @var string|null
     */
    private $endpoint;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * Sets whether the response from the `Subscribe` request includes the subscription ARN, even if the subscription is not
     * yet confirmed.
     *
     * @var bool|null
     */
    private $returnSubscriptionArn;

    /**
     * @param array{
     *   TopicArn?: string,
     *   Protocol?: string,
     *   Endpoint?: string,
     *   Attributes?: array<string, string>,
     *   ReturnSubscriptionArn?: bool,
     *   @region?: string,
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
            throw new InvalidArgument(sprintf('Missing parameter "TopicArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TopicArn'] = $v;
        if (null === $v = $this->protocol) {
            throw new InvalidArgument(sprintf('Missing parameter "Protocol" for "%s". The value cannot be null.', __CLASS__));
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
