<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Input for Unsubscribe action.
 */
final class UnsubscribeInput extends Input
{
    /**
     * The ARN of the subscription to be deleted.
     *
     * @required
     *
     * @var string|null
     */
    private $subscriptionArn;

    /**
     * @param array{
     *   SubscriptionArn?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->subscriptionArn = $input['SubscriptionArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SubscriptionArn?: string,
     *   '@region'?: string|null,
     * }|UnsubscribeInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSubscriptionArn(): ?string
    {
        return $this->subscriptionArn;
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
        $body = http_build_query(['Action' => 'Unsubscribe', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setSubscriptionArn(?string $value): self
    {
        $this->subscriptionArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->subscriptionArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "SubscriptionArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SubscriptionArn'] = $v;

        return $payload;
    }
}
