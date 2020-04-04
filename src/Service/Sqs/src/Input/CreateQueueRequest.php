<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class CreateQueueRequest implements Input
{
    /**
     * The name of the new queue. The following limits apply to this name:.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueName;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var string[]
     */
    private $Attributes;

    /**
     * Add cost allocation tags to the specified Amazon SQS queue. For an overview, see Tagging Your Amazon SQS Queues in
     * the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-tags.html
     *
     * @var string[]
     */
    private $tags;

    /**
     * @param array{
     *   QueueName?: string,
     *   Attributes?: string[],
     *   tags?: string[],
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueName = $input['QueueName'] ?? null;
        $this->Attributes = $input['Attributes'] ?? [];
        $this->tags = $input['tags'] ?? [];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->Attributes;
    }

    public function getQueueName(): ?string
    {
        return $this->QueueName;
    }

    /**
     * @return string[]
     */
    public function gettags(): array
    {
        return $this->tags;
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
        $body = http_build_query(['Action' => 'CreateQueue', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param string[] $value
     */
    public function setAttributes(array $value): self
    {
        $this->Attributes = $value;

        return $this;
    }

    public function setQueueName(?string $value): self
    {
        $this->QueueName = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function settags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->QueueName) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueName'] = $v;

        $index = 0;
        foreach ($this->Attributes as $mapKey => $mapValue) {
            ++$index;
            $payload["Attribute.$index.Name"] = $mapKey;
            $payload["Attribute.$index.Value"] = $mapValue;
        }

        $index = 0;
        foreach ($this->tags as $mapKey => $mapValue) {
            ++$index;
            $payload["Tag.$index.Key"] = $mapKey;
            $payload["Tag.$index.Value"] = $mapValue;
        }

        return $payload;
    }
}
