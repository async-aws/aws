<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListStreamConsumersInput extends Input
{
    /**
     * The ARN of the Kinesis data stream for which you want to list the registered consumers. For more information, see
     * Amazon Resource Names (ARNs) and Amazon Web Services Service Namespaces.
     *
     * @see https://docs.aws.amazon.com/general/latest/gr/aws-arns-and-namespaces.html#arn-syntax-kinesis-streams
     *
     * @required
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * When the number of consumers that are registered with the data stream is greater than the default value for the
     * `MaxResults` parameter, or if you explicitly specify a value for `MaxResults` that is less than the number of
     * consumers that are registered with the data stream, the response includes a pagination token named `NextToken`. You
     * can specify this `NextToken` value in a subsequent call to `ListStreamConsumers` to list the next set of registered
     * consumers.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of consumers that you want a single call of `ListStreamConsumers` to return. The default value is
     * 100. If you specify a value greater than 100, at most 100 results are returned.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * Specify this input parameter to distinguish data streams that have the same name. For example, if you create a data
     * stream and then delete it, and you later create another data stream with the same name, you can use this input
     * parameter to specify which of the two streams you want to list the consumers for.
     *
     * @var \DateTimeImmutable|null
     */
    private $streamCreationTimestamp;

    /**
     * @param array{
     *   StreamARN?: string,
     *   NextToken?: string,
     *   MaxResults?: int,
     *   StreamCreationTimestamp?: \DateTimeImmutable|string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamArn = $input['StreamARN'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->streamCreationTimestamp = !isset($input['StreamCreationTimestamp']) ? null : ($input['StreamCreationTimestamp'] instanceof \DateTimeImmutable ? $input['StreamCreationTimestamp'] : new \DateTimeImmutable($input['StreamCreationTimestamp']));
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    public function getStreamCreationTimestamp(): ?\DateTimeImmutable
    {
        return $this->streamCreationTimestamp;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.ListStreamConsumers',
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

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

        return $this;
    }

    public function setStreamCreationTimestamp(?\DateTimeImmutable $value): self
    {
        $this->streamCreationTimestamp = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamArn) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamARN" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamARN'] = $v;
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->streamCreationTimestamp) {
            $payload['StreamCreationTimestamp'] = $v->format(\DateTimeInterface::ATOM);
        }

        return $payload;
    }
}
