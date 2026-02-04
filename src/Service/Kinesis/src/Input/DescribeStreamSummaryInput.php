<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DescribeStreamSummaryInput extends Input
{
    /**
     * The name of the stream to describe.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * Not Implemented. Reserved for future use.
     *
     * @var string|null
     */
    private $streamId;

    /**
     * @param array{
     *   StreamName?: string|null,
     *   StreamARN?: string|null,
     *   StreamId?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        $this->streamId = $input['StreamId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: string|null,
     *   StreamARN?: string|null,
     *   StreamId?: string|null,
     *   '@region'?: string|null,
     * }|DescribeStreamSummaryInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    public function getStreamId(): ?string
    {
        return $this->streamId;
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
            'X-Amz-Target' => 'Kinesis_20131202.DescribeStreamSummary',
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

    public function setStreamId(?string $value): self
    {
        $this->streamId = $value;

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
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }
        if (null !== $v = $this->streamId) {
            $payload['StreamId'] = $v;
        }

        return $payload;
    }
}
