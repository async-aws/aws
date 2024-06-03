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
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: null|string,
     *   TagKeys?: string[],
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->tagKeys = $input['TagKeys'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: null|string,
     *   TagKeys?: string[],
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|RemoveTagsFromStreamInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
            'Accept' => 'application/json',
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
        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
        if (null === $v = $this->tagKeys) {
            throw new InvalidArgument(sprintf('Missing parameter "TagKeys" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['TagKeys'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['TagKeys'][$index] = $listValue;
        }

        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
