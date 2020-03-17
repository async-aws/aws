<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

class ChangeMessageVisibilityRequest
{
    /**
     * The URL of the Amazon SQS queue whose message's visibility is changed.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * The receipt handle associated with the message whose visibility timeout is changed. This parameter is returned by the
     * `ReceiveMessage` action.
     *
     * @required
     *
     * @var string|null
     */
    private $ReceiptHandle;

    /**
     * The new value for the message's visibility timeout (in seconds). Values values: `0` to `43200`. Maximum: 12 hours.
     *
     * @required
     *
     * @var int|null
     */
    private $VisibilityTimeout;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   ReceiptHandle?: string,
     *   VisibilityTimeout?: int,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->ReceiptHandle = $input['ReceiptHandle'] ?? null;
        $this->VisibilityTimeout = $input['VisibilityTimeout'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
    }

    public function getReceiptHandle(): ?string
    {
        return $this->ReceiptHandle;
    }

    public function getVisibilityTimeout(): ?int
    {
        return $this->VisibilityTimeout;
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

        // Prepare Body
        $body = http_build_query(['Action' => 'ChangeMessageVisibility', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    public function setReceiptHandle(?string $value): self
    {
        $this->ReceiptHandle = $value;

        return $this;
    }

    public function setVisibilityTimeout(?int $value): self
    {
        $this->VisibilityTimeout = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->QueueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->ReceiptHandle) {
            throw new InvalidArgument(sprintf('Missing parameter "ReceiptHandle" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->VisibilityTimeout) {
            throw new InvalidArgument(sprintf('Missing parameter "VisibilityTimeout" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }

    /**
     * @internal
     */
    private function requestBody(): array
    {
        $payload = [];
        $payload['QueueUrl'] = $this->QueueUrl;
        $payload['ReceiptHandle'] = $this->ReceiptHandle;
        $payload['VisibilityTimeout'] = $this->VisibilityTimeout;

        return $payload;
    }
}
