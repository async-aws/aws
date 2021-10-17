<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `CreateStream`.
 */
final class CreateStreamInput extends Input
{
    /**
     * A name to identify the stream. The stream name is scoped to the AWS account used by the application that creates the
     * stream. It is also scoped by AWS Region. That is, two streams in two different AWS accounts can have the same name.
     * Two streams in the same AWS account but in two different Regions can also have the same name.
     *
     * @required
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The number of shards that the stream will use. The throughput of the stream is a function of the number of shards;
     * more shards are required for greater provisioned throughput.
     *
     * @required
     *
     * @var int|null
     */
    private $shardCount;

    /**
     * @param array{
     *   StreamName?: string,
     *   ShardCount?: int,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->shardCount = $input['ShardCount'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getShardCount(): ?int
    {
        return $this->shardCount;
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
            'X-Amz-Target' => 'Kinesis_20131202.CreateStream',
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

    public function setShardCount(?int $value): self
    {
        $this->shardCount = $value;

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
        if (null === $v = $this->streamName) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamName'] = $v;
        if (null === $v = $this->shardCount) {
            throw new InvalidArgument(sprintf('Missing parameter "ShardCount" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ShardCount'] = $v;

        return $payload;
    }
}
