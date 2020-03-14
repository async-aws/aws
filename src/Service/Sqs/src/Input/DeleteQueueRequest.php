<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

class DeleteQueueRequest
{
    /**
     * The URL of the Amazon SQS queue to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * @param array{
     *   QueueUrl?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
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

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->QueueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }

    private function requestBody(): string
    {
        $payload = ['Action' => 'DeleteQueue', 'Version' => '2012-11-05'];
        $payload['QueueUrl'] = $this->QueueUrl;

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }
}
