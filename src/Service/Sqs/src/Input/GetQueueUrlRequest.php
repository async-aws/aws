<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetQueueUrlRequest extends Input
{
    /**
     * The name of the queue whose URL must be fetched. Maximum 80 characters. Valid values: alphanumeric characters,
     * hyphens (`-`), and underscores (`_`).
     *
     * Queue URLs and names are case-sensitive.
     *
     * @required
     *
     * @var string|null
     */
    private $queueName;

    /**
     * The Amazon Web Services account ID of the account that created the queue.
     *
     * @var string|null
     */
    private $queueOwnerAwsAccountId;

    /**
     * @param array{
     *   QueueName?: string,
     *   QueueOwnerAWSAccountId?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueName = $input['QueueName'] ?? null;
        $this->queueOwnerAwsAccountId = $input['QueueOwnerAWSAccountId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueueName?: string,
     *   QueueOwnerAWSAccountId?: null|string,
     *   '@region'?: string|null,
     * }|GetQueueUrlRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueName(): ?string
    {
        return $this->queueName;
    }

    public function getQueueOwnerAwsAccountId(): ?string
    {
        return $this->queueOwnerAwsAccountId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.GetQueueUrl',
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

    public function setQueueName(?string $value): self
    {
        $this->queueName = $value;

        return $this;
    }

    public function setQueueOwnerAwsAccountId(?string $value): self
    {
        $this->queueOwnerAwsAccountId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queueName) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueName'] = $v;
        if (null !== $v = $this->queueOwnerAwsAccountId) {
            $payload['QueueOwnerAWSAccountId'] = $v;
        }

        return $payload;
    }
}
