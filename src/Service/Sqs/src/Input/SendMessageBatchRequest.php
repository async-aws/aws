<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\ValueObject\SendMessageBatchRequestEntry;

final class SendMessageBatchRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue to which batched messages are sent.
     *
     * @required
     *
     * @var string|null
     */
    private $queueUrl;

    /**
     * A list of `SendMessageBatchRequestEntry` items.
     *
     * @required
     *
     * @var SendMessageBatchRequestEntry[]|null
     */
    private $entries;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   Entries?: SendMessageBatchRequestEntry[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->entries = isset($input['Entries']) ? array_map([SendMessageBatchRequestEntry::class, 'create'], $input['Entries']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return SendMessageBatchRequestEntry[]
     */
    public function getEntries(): array
    {
        return $this->entries ?? [];
    }

    public function getQueueUrl(): ?string
    {
        return $this->queueUrl;
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
        $body = http_build_query(['Action' => 'SendMessageBatch', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param SendMessageBatchRequestEntry[] $value
     */
    public function setEntries(array $value): self
    {
        $this->entries = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->queueUrl = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queueUrl) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null === $v = $this->entries) {
            throw new InvalidArgument(sprintf('Missing parameter "Entries" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = 0;
        foreach ($v as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["SendMessageBatchRequestEntry.$index.$bodyKey"] = $bodyValue;
            }
        }

        return $payload;
    }
}
