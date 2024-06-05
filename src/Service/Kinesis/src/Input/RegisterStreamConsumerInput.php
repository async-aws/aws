<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class RegisterStreamConsumerInput extends Input
{
    /**
     * The ARN of the Kinesis data stream that you want to register the consumer with. For more info, see Amazon Resource
     * Names (ARNs) and Amazon Web Services Service Namespaces [^1].
     *
     * [^1]: https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html#arn-syntax-kinesis-streams
     *
     * @required
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * For a given Kinesis data stream, each consumer must have a unique name. However, consumer names don't have to be
     * unique across data streams.
     *
     * @required
     *
     * @var string|null
     */
    private $consumerName;

    /**
     * @param array{
     *   StreamARN?: string,
     *   ConsumerName?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamArn = $input['StreamARN'] ?? null;
        $this->consumerName = $input['ConsumerName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamARN?: string,
     *   ConsumerName?: string,
     *   '@region'?: string|null,
     * }|RegisterStreamConsumerInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConsumerName(): ?string
    {
        return $this->consumerName;
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
            'X-Amz-Target' => 'Kinesis_20131202.RegisterStreamConsumer',
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

    public function setConsumerName(?string $value): self
    {
        $this->consumerName = $value;

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
        if (null === $v = $this->streamArn) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamARN" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamARN'] = $v;
        if (null === $v = $this->consumerName) {
            throw new InvalidArgument(sprintf('Missing parameter "ConsumerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ConsumerName'] = $v;

        return $payload;
    }
}
