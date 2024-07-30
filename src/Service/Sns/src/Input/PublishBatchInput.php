<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sns\ValueObject\PublishBatchRequestEntry;

final class PublishBatchInput extends Input
{
    /**
     * The Amazon resource name (ARN) of the topic you want to batch publish to.
     *
     * @required
     *
     * @var string|null
     */
    private $topicArn;

    /**
     * A list of `PublishBatch` request entries to be sent to the SNS topic.
     *
     * @required
     *
     * @var PublishBatchRequestEntry[]|null
     */
    private $publishBatchRequestEntries;

    /**
     * @param array{
     *   TopicArn?: string,
     *   PublishBatchRequestEntries?: array<PublishBatchRequestEntry|array>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->topicArn = $input['TopicArn'] ?? null;
        $this->publishBatchRequestEntries = isset($input['PublishBatchRequestEntries']) ? array_map([PublishBatchRequestEntry::class, 'create'], $input['PublishBatchRequestEntries']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TopicArn?: string,
     *   PublishBatchRequestEntries?: array<PublishBatchRequestEntry|array>,
     *   '@region'?: string|null,
     * }|PublishBatchInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return PublishBatchRequestEntry[]
     */
    public function getPublishBatchRequestEntries(): array
    {
        return $this->publishBatchRequestEntries ?? [];
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
        $body = http_build_query(['Action' => 'PublishBatch', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param PublishBatchRequestEntry[] $value
     */
    public function setPublishBatchRequestEntries(array $value): self
    {
        $this->publishBatchRequestEntries = $value;

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
        if (null === $v = $this->publishBatchRequestEntries) {
            throw new InvalidArgument(\sprintf('Missing parameter "PublishBatchRequestEntries" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = 0;
        foreach ($v as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["PublishBatchRequestEntries.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        return $payload;
    }
}
