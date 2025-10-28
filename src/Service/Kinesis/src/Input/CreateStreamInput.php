<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kinesis\ValueObject\StreamModeDetails;

/**
 * Represents the input for `CreateStream`.
 */
final class CreateStreamInput extends Input
{
    /**
     * A name to identify the stream. The stream name is scoped to the Amazon Web Services account used by the application
     * that creates the stream. It is also scoped by Amazon Web Services Region. That is, two streams in two different
     * Amazon Web Services accounts can have the same name. Two streams in the same Amazon Web Services account but in two
     * different Regions can also have the same name.
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
     * @var int|null
     */
    private $shardCount;

    /**
     * Indicates the capacity mode of the data stream. Currently, in Kinesis Data Streams, you can choose between an
     * **on-demand** capacity mode and a **provisioned** capacity mode for your data streams.
     *
     * @var StreamModeDetails|null
     */
    private $streamModeDetails;

    /**
     * A set of up to 50 key-value pairs to use to create the tags. A tag consists of a required key and an optional value.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * The maximum record size of a single record in kibibyte (KiB) that you can write to, and read from a stream.
     *
     * @var int|null
     */
    private $maxRecordSizeInKiB;

    /**
     * @param array{
     *   StreamName?: string,
     *   ShardCount?: int|null,
     *   StreamModeDetails?: StreamModeDetails|array|null,
     *   Tags?: array<string, string>|null,
     *   MaxRecordSizeInKiB?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->shardCount = $input['ShardCount'] ?? null;
        $this->streamModeDetails = isset($input['StreamModeDetails']) ? StreamModeDetails::create($input['StreamModeDetails']) : null;
        $this->tags = $input['Tags'] ?? null;
        $this->maxRecordSizeInKiB = $input['MaxRecordSizeInKiB'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: string,
     *   ShardCount?: int|null,
     *   StreamModeDetails?: StreamModeDetails|array|null,
     *   Tags?: array<string, string>|null,
     *   MaxRecordSizeInKiB?: int|null,
     *   '@region'?: string|null,
     * }|CreateStreamInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxRecordSizeInKiB(): ?int
    {
        return $this->maxRecordSizeInKiB;
    }

    public function getShardCount(): ?int
    {
        return $this->shardCount;
    }

    public function getStreamModeDetails(): ?StreamModeDetails
    {
        return $this->streamModeDetails;
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
            'X-Amz-Target' => 'Kinesis_20131202.CreateStream',
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

    public function setMaxRecordSizeInKiB(?int $value): self
    {
        $this->maxRecordSizeInKiB = $value;

        return $this;
    }

    public function setShardCount(?int $value): self
    {
        $this->shardCount = $value;

        return $this;
    }

    public function setStreamModeDetails(?StreamModeDetails $value): self
    {
        $this->streamModeDetails = $value;

        return $this;
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
            throw new InvalidArgument(\sprintf('Missing parameter "StreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamName'] = $v;
        if (null !== $v = $this->shardCount) {
            $payload['ShardCount'] = $v;
        }
        if (null !== $v = $this->streamModeDetails) {
            $payload['StreamModeDetails'] = $v->requestBody();
        }
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['Tags'] = new \stdClass();
            } else {
                $payload['Tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Tags'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->maxRecordSizeInKiB) {
            $payload['MaxRecordSizeInKiB'] = $v;
        }

        return $payload;
    }
}
