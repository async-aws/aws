<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `ListTagsForStream`.
 */
final class ListTagsForStreamInput extends Input
{
    /**
     * The name of the stream.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The key to use as the starting point for the list of tags. If this parameter is set, `ListTagsForStream` gets all
     * tags that occur after `ExclusiveStartTagKey`.
     *
     * @var string|null
     */
    private $exclusiveStartTagKey;

    /**
     * The number of tags to return. If this number is less than the total number of tags associated with the stream,
     * `HasMoreTags` is set to `true`. To list additional tags, set `ExclusiveStartTagKey` to the last key in the response.
     *
     * @var int|null
     */
    private $limit;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: string,
     *   ExclusiveStartTagKey?: string,
     *   Limit?: int,
     *   StreamARN?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->exclusiveStartTagKey = $input['ExclusiveStartTagKey'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExclusiveStartTagKey(): ?string
    {
        return $this->exclusiveStartTagKey;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.ListTagsForStream',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setExclusiveStartTagKey(?string $value): self
    {
        $this->exclusiveStartTagKey = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

        return $this;
    }

    public function setStreamName(?string $value): self
    {
        $this->streamName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
        if (null !== $v = $this->exclusiveStartTagKey) {
            $payload['ExclusiveStartTagKey'] = $v;
        }
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
