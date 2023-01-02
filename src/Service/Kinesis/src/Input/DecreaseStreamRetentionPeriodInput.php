<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for DecreaseStreamRetentionPeriod.
 */
final class DecreaseStreamRetentionPeriodInput extends Input
{
    /**
     * The name of the stream to modify.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The new retention period of the stream, in hours. Must be less than the current retention period.
     *
     * @required
     *
     * @var int|null
     */
    private $retentionPeriodHours;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: string,
     *   RetentionPeriodHours?: int,
     *   StreamARN?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->retentionPeriodHours = $input['RetentionPeriodHours'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRetentionPeriodHours(): ?int
    {
        return $this->retentionPeriodHours;
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
            'X-Amz-Target' => 'Kinesis_20131202.DecreaseStreamRetentionPeriod',
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

    public function setRetentionPeriodHours(?int $value): self
    {
        $this->retentionPeriodHours = $value;

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
        if (null === $v = $this->retentionPeriodHours) {
            throw new InvalidArgument(sprintf('Missing parameter "RetentionPeriodHours" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RetentionPeriodHours'] = $v;
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
