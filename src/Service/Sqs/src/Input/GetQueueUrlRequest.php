<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

class GetQueueUrlRequest
{
    /**
     * The name of the queue whose URL must be fetched. Maximum 80 characters. Valid values: alphanumeric characters,
     * hyphens (`-`), and underscores (`_`).
     *
     * @required
     *
     * @var string|null
     */
    private $QueueName;

    /**
     * The AWS account ID of the account that created the queue.
     *
     * @var string|null
     */
    private $QueueOwnerAWSAccountId;

    /**
     * @param array{
     *   QueueName?: string,
     *   QueueOwnerAWSAccountId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueName = $input['QueueName'] ?? null;
        $this->QueueOwnerAWSAccountId = $input['QueueOwnerAWSAccountId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueName(): ?string
    {
        return $this->QueueName;
    }

    public function getQueueOwnerAWSAccountId(): ?string
    {
        return $this->QueueOwnerAWSAccountId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($this->requestBody()));
    }

    public function setQueueName(?string $value): self
    {
        $this->QueueName = $value;

        return $this;
    }

    public function setQueueOwnerAWSAccountId(?string $value): self
    {
        $this->QueueOwnerAWSAccountId = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->QueueName) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueName" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }

    private function requestBody(): string
    {
        $payload = ['Action' => 'GetQueueUrl', 'Version' => '2012-11-05'];
        $payload['QueueName'] = $this->QueueName;
        if (null !== $v = $this->QueueOwnerAWSAccountId) {
            $payload['QueueOwnerAWSAccountId'] = $v;
        }

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }
}
