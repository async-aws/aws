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
     * A set of up to 50 key-value pairs. A tag consists of a required key and an optional value.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   StreamARN?: string,
     *   ConsumerName?: string,
     *   Tags?: null|array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamArn = $input['StreamARN'] ?? null;
        $this->consumerName = $input['ConsumerName'] ?? null;
        $this->tags = $input['Tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamARN?: string,
     *   ConsumerName?: string,
     *   Tags?: null|array<string, string>,
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
        if (null === $v = $this->streamArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "StreamARN" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamARN'] = $v;
        if (null === $v = $this->consumerName) {
            throw new InvalidArgument(\sprintf('Missing parameter "ConsumerName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ConsumerName'] = $v;
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

        return $payload;
    }
}
