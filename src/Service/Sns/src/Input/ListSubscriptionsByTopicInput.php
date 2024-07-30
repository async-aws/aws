<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Input for ListSubscriptionsByTopic action.
 */
final class ListSubscriptionsByTopicInput extends Input
{
    /**
     * The ARN of the topic for which you wish to find subscriptions.
     *
     * @required
     *
     * @var string|null
     */
    private $topicArn;

    /**
     * Token returned by the previous `ListSubscriptionsByTopic` request.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   TopicArn?: string,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->topicArn = $input['TopicArn'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TopicArn?: string,
     *   NextToken?: null|string,
     *   '@region'?: string|null,
     * }|ListSubscriptionsByTopicInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
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
        $body = http_build_query(['Action' => 'ListSubscriptionsByTopic', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

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
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }

        return $payload;
    }
}
