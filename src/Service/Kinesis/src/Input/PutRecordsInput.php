<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kinesis\ValueObject\PutRecordsRequestEntry;

/**
 * A `PutRecords` request.
 */
final class PutRecordsInput extends Input
{
    /**
     * The records associated with the request.
     *
     * @required
     *
     * @var PutRecordsRequestEntry[]|null
     */
    private $records;

    /**
     * The stream name associated with the request.
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
     * @param array{
     *   Records?: PutRecordsRequestEntry[],
     *   StreamName?: string,
     *   StreamARN?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->records = isset($input['Records']) ? array_map([PutRecordsRequestEntry::class, 'create'], $input['Records']) : null;
        $this->streamName = $input['StreamName'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return PutRecordsRequestEntry[]
     */
    public function getRecords(): array
    {
        return $this->records ?? [];
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
            'X-Amz-Target' => 'Kinesis_20131202.PutRecords',
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

    /**
     * @param PutRecordsRequestEntry[] $value
     */
    public function setRecords(array $value): self
    {
        $this->records = $value;

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
        if (null === $v = $this->records) {
            throw new InvalidArgument(sprintf('Missing parameter "Records" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['Records'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['Records'][$index] = $listValue->requestBody();
        }

        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
