<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for GetRecords.
 */
final class GetRecordsInput extends Input
{
    /**
     * The position in the shard from which you want to start sequentially reading data records. A shard iterator specifies
     * this position using the sequence number of a data record in the shard.
     *
     * @required
     *
     * @var string|null
     */
    private $shardIterator;

    /**
     * The maximum number of records to return. Specify a value of up to 10,000. If you specify a value that is greater than
     * 10,000, GetRecords throws `InvalidArgumentException`. The default value is 10,000.
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
     *   ShardIterator?: string,
     *   Limit?: int,
     *   StreamARN?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->shardIterator = $input['ShardIterator'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getShardIterator(): ?string
    {
        return $this->shardIterator;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.GetRecords',
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

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setShardIterator(?string $value): self
    {
        $this->shardIterator = $value;

        return $this;
    }

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->shardIterator) {
            throw new InvalidArgument(sprintf('Missing parameter "ShardIterator" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ShardIterator'] = $v;
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
