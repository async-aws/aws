<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for DeleteStream.
 */
final class DeleteStreamInput extends Input
{
    /**
     * The name of the stream to delete.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * If this parameter is unset (`null`) or if you set it to `false`, and the stream has registered consumers, the call to
     * `DeleteStream` fails with a `ResourceInUseException`.
     *
     * @var bool|null
     */
    private $enforceConsumerDeletion;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: null|string,
     *   EnforceConsumerDeletion?: null|bool,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->enforceConsumerDeletion = $input['EnforceConsumerDeletion'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: null|string,
     *   EnforceConsumerDeletion?: null|bool,
     *   StreamARN?: null|string,
     *   '@region'?: string|null,
     * }|DeleteStreamInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnforceConsumerDeletion(): ?bool
    {
        return $this->enforceConsumerDeletion;
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
            'X-Amz-Target' => 'Kinesis_20131202.DeleteStream',
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

    public function setEnforceConsumerDeletion(?bool $value): self
    {
        $this->enforceConsumerDeletion = $value;

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
        if (null !== $v = $this->enforceConsumerDeletion) {
            $payload['EnforceConsumerDeletion'] = (bool) $v;
        }
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
