<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListMessageMoveTasksRequest extends Input
{
    /**
     * The ARN of the queue whose message movement tasks are to be listed.
     *
     * @required
     *
     * @var string|null
     */
    private $sourceArn;

    /**
     * The maximum number of results to include in the response. The default is 1, which provides the most recent message
     * movement task. The upper limit is 10.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * @param array{
     *   SourceArn?: string,
     *   MaxResults?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->sourceArn = $input['SourceArn'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   SourceArn?: string,
     *   MaxResults?: int|null,
     *   '@region'?: string|null,
     * }|ListMessageMoveTasksRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getSourceArn(): ?string
    {
        return $this->sourceArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.ListMessageMoveTasks',
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

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setSourceArn(?string $value): self
    {
        $this->sourceArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->sourceArn) {
            throw new InvalidArgument(\sprintf('Missing parameter "SourceArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['SourceArn'] = $v;
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }

        return $payload;
    }
}
