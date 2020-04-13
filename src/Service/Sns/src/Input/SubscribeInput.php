<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class SubscribeInput extends Input
{
    /**
     * The ARN of the topic you want to subscribe to.
     *
     * @required
     *
     * @var string|null
     */
    private $TopicArn;

    /**
     * The protocol you want to use. Supported protocols include:.
     *
     * @required
     *
     * @var string|null
     */
    private $Protocol;

    /**
     * The endpoint that you want to receive notifications. Endpoints vary by protocol:.
     *
     * @var string|null
     */
    private $Endpoint;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var string[]
     */
    private $Attributes;

    /**
     * Sets whether the response from the `Subscribe` request includes the subscription ARN, even if the subscription is not
     * yet confirmed.
     *
     * @var bool|null
     */
    private $ReturnSubscriptionArn;

    /**
     * @param array{
     *   TopicArn?: string,
     *   Protocol?: string,
     *   Endpoint?: string,
     *   Attributes?: string[],
     *   ReturnSubscriptionArn?: bool,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->TopicArn = $input['TopicArn'] ?? null;
        $this->Protocol = $input['Protocol'] ?? null;
        $this->Endpoint = $input['Endpoint'] ?? null;
        $this->Attributes = $input['Attributes'] ?? [];
        $this->ReturnSubscriptionArn = $input['ReturnSubscriptionArn'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->Attributes;
    }

    public function getEndpoint(): ?string
    {
        return $this->Endpoint;
    }

    public function getProtocol(): ?string
    {
        return $this->Protocol;
    }

    public function getReturnSubscriptionArn(): ?bool
    {
        return $this->ReturnSubscriptionArn;
    }

    public function getTopicArn(): ?string
    {
        return $this->TopicArn;
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
     * @param string[] $value
     */
    public function setAttributes(array $value): self
    {
        $this->Attributes = $value;

        return $this;
    }

    public function setEndpoint(?string $value): self
    {
        $this->Endpoint = $value;

        return $this;
    }

    public function setProtocol(?string $value): self
    {
        $this->Protocol = $value;

        return $this;
    }

    public function setReturnSubscriptionArn(?bool $value): self
    {
        $this->ReturnSubscriptionArn = $value;

        return $this;
    }

    public function setTopicArn(?string $value): self
    {
        $this->TopicArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->TopicArn) {
            throw new InvalidArgument(sprintf('Missing parameter "TopicArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TopicArn'] = $v;
        if (null === $v = $this->Protocol) {
            throw new InvalidArgument(sprintf('Missing parameter "Protocol" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Protocol'] = $v;
        if (null !== $v = $this->Endpoint) {
            $payload['Endpoint'] = $v;
        }

        $index = 0;
        foreach ($this->Attributes as $mapKey => $mapValue) {
            ++$index;
            $payload["Attributes.entry.$index.key"] = $mapKey;
            $payload["Attributes.entry.$index.value"] = $mapValue;
        }

        if (null !== $v = $this->ReturnSubscriptionArn) {
            $payload['ReturnSubscriptionArn'] = $v ? 'true' : 'false';
        }

        return $payload;
    }
}
