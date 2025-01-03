<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Retrieves the URL of an existing queue based on its name and, optionally, the Amazon Web Services account ID.
 */
final class GetQueueUrlRequest extends Input
{
    /**
     * (Required) The name of the queue for which you want to fetch the URL. The name can be up to 80 characters long and
     * can include alphanumeric characters, hyphens (-), and underscores (_). Queue URLs and names are case-sensitive.
     *
     * @required
     *
     * @var string|null
     */
    private $queueName;

    /**
     * (Optional) The Amazon Web Services account ID of the account that created the queue. This is only required when you
     * are attempting to access a queue owned by another Amazon Web Services account.
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
            throw new InvalidArgument(\sprintf('Missing parameter "QueueName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueName'] = $v;
        if (null !== $v = $this->queueOwnerAwsAccountId) {
            $payload['QueueOwnerAWSAccountId'] = $v;
        }

        return $payload;
    }
}
