<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteMessageRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue from which messages are deleted.
     *
     * @required
     *
     * @var string|null
     */
    private $queueUrl;

    /**
     * The receipt handle associated with the message to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $receiptHandle;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   ReceiptHandle?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->receiptHandle = $input['ReceiptHandle'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueUrl(): ?string
    {
        return $this->queueUrl;
    }

    public function getReceiptHandle(): ?string
    {
        return $this->receiptHandle;
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
        $body = http_build_query(['Action' => 'DeleteMessage', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setQueueUrl(?string $value): self
    {
        $this->queueUrl = $value;

        return $this;
    }

    public function setReceiptHandle(?string $value): self
    {
        $this->receiptHandle = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null === $v = $this->receiptHandle) {
            throw new InvalidArgument(sprintf('Missing parameter "ReceiptHandle" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ReceiptHandle'] = $v;

        return $payload;
    }
}
