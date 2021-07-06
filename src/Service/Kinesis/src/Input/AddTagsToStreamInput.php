<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `AddTagsToStream`.
 */
final class AddTagsToStreamInput extends Input
{
    /**
     * The name of the stream.
     *
     * @required
     *
     * @var string|null
     */
    private $streamName;

    /**
     * A set of up to 10 key-value pairs to use to create the tags.
     *
     * @required
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   StreamName?: string,
     *   Tags?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->tags = $input['Tags'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.AddTagsToStream',
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

    public function setStreamName(?string $value): self
    {
        $this->streamName = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamName) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamName'] = $v;
        if (null === $v = $this->tags) {
            throw new InvalidArgument(sprintf('Missing parameter "Tags" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['Tags'] = new \stdClass();
        } else {
            $payload['Tags'] = [];
            foreach ($v as $name => $mv) {
                $payload['Tags'][$name] = $mv;
            }
        }

        return $payload;
    }
}
