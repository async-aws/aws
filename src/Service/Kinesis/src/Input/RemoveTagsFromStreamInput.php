<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `RemoveTagsFromStream`.
 */
final class RemoveTagsFromStreamInput extends Input
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
     * A list of tag keys. Each corresponding tag is removed from the stream.
     *
     * @required
     *
     * @var string[]|null
     */
    private $tagKeys;

    /**
     * @param array{
     *   StreamName?: string,
     *   TagKeys?: string[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->tagKeys = $input['TagKeys'] ?? null;
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
     * @return string[]
     */
    public function getTagKeys(): array
    {
        return $this->tagKeys ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.RemoveTagsFromStream',
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
     * @param string[] $value
     */
    public function setTagKeys(array $value): self
    {
        $this->tagKeys = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamName) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamName'] = $v;
        if (null === $v = $this->tagKeys) {
            throw new InvalidArgument(sprintf('Missing parameter "TagKeys" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['TagKeys'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['TagKeys'][$index] = $listValue;
        }

        return $payload;
    }
}
