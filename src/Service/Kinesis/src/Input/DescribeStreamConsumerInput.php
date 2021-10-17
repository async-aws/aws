<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DescribeStreamConsumerInput extends Input
{
    /**
     * The ARN of the Kinesis data stream that the consumer is registered with. For more information, see Amazon Resource
     * Names (ARNs) and AWS Service Namespaces.
     *
     * @see https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html#arn-syntax-kinesis-streams
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * The name that you gave to the consumer.
     *
     * @var string|null
     */
    private $consumerName;

    /**
     * The ARN returned by Kinesis Data Streams when you registered the consumer.
     *
     * @var string|null
     */
    private $consumerArn;

    /**
     * @param array{
     *   StreamARN?: string,
     *   ConsumerName?: string,
     *   ConsumerARN?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamArn = $input['StreamARN'] ?? null;
        $this->consumerName = $input['ConsumerName'] ?? null;
        $this->consumerArn = $input['ConsumerARN'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConsumerArn(): ?string
    {
        return $this->consumerArn;
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
            'X-Amz-Target' => 'Kinesis_20131202.DescribeStreamConsumer',
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

    public function setConsumerArn(?string $value): self
    {
        $this->consumerArn = $value;

        return $this;
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
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }
        if (null !== $v = $this->consumerName) {
            $payload['ConsumerName'] = $v;
        }
        if (null !== $v = $this->consumerArn) {
            $payload['ConsumerARN'] = $v;
        }

        return $payload;
    }
}
